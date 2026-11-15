<?php

namespace App\Services\Auth;

use App\Mail\LoginCodeMail;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Passwordless sign-in: email → 6-digit code → account (created on first use).
 */
class LoginCodeService
{
    public const LENGTH = 6;

    public const TTL_MINUTES = 10;

    public const MAX_ATTEMPTS = 5;

    public function __construct(
        private readonly AccountLogin $accounts,
    ) {}

    public function send(string $email, ?string $ip): void
    {
        $email = Str::lower(trim($email));
        $code = str_pad((string) random_int(0, 10 ** self::LENGTH - 1), self::LENGTH, '0', STR_PAD_LEFT);

        // Only the newest code is valid.
        LoginCode::where('email', $email)->whereNull('consumed_at')->update(['consumed_at' => now()]);

        LoginCode::create([
            'email' => $email,
            'code_hash' => Hash::make($code),
            'ip_address' => $ip,
            'expires_at' => now()->addMinutes(self::TTL_MINUTES),
        ]);

        Mail::to($email)->send(new LoginCodeMail($code, self::TTL_MINUTES));
    }

    /**
     * The user when the code is valid, null otherwise. A code works once,
     * expires after TTL_MINUTES, and dies after MAX_ATTEMPTS wrong tries.
     */
    public function verify(string $email, string $code): ?User
    {
        $email = Str::lower(trim($email));

        $record = LoginCode::where('email', $email)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (! $record || $record->attempts >= self::MAX_ATTEMPTS) {
            return null;
        }

        if (! Hash::check((string) preg_replace('/\D/', '', $code), $record->code_hash)) {
            $record->increment('attempts');

            return null;
        }

        $record->update(['consumed_at' => now()]);

        return $this->accounts->findOrCreate($email);
    }
}
