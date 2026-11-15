<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Shared by the passwordless sign-ins (email code, Google, Apple): every one
 * of them proves the person owns the email address, so an unknown address
 * gets a new client account and an unverified one becomes verified.
 */
class AccountLogin
{
    public function findOrCreate(string $email, ?string $name = null): User
    {
        $email = Str::lower(trim($email));
        $user = User::where('email', $email)->first();

        if (! $user) {
            return User::create([
                'name' => filled($name) ? Str::limit(trim($name), 255, '') : $this->nameFromEmail($email),
                'email' => $email,
                // No password: the account signs in by code / Google / Apple, or
                // sets one later through "forgot password".
                'password' => Str::random(48),
                'role' => 'client',
                'email_verified_at' => now(),
            ]);
        }

        if (! $user->email_verified_at) {
            $user->update([
                'email_verified_at' => now(),
                'verification_token' => null,
                'verification_token_expires_at' => null,
            ]);
        }

        return $user;
    }

    /**
     * Start the session and send the user to their dashboard.
     */
    public function login(Request $request, User $user): RedirectResponse
    {
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended($this->homeFor($user));
    }

    public function homeFor(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'counselor' => route('consultant.dashboard'),
            default => route('client.dashboard'),
        };
    }

    private function nameFromEmail(string $email): string
    {
        return Str::of($email)->before('@')->replace(['.', '_', '-', '+'], ' ')->squish()->title()->toString() ?: 'عميل';
    }
}
