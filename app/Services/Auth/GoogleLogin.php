<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as GoogleUser;
use Laravel\Socialite\Facades\Socialite;

/**
 * "Continue with Google": sign in or sign up with a Google account.
 * Keys: GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET (config/services.php).
 */
class GoogleLogin
{
    public function __construct(
        private readonly AccountLogin $accounts,
    ) {}

    public static function enabled(): bool
    {
        return filled(config('services.google.client_id')) && filled(config('services.google.client_secret'));
    }

    public function provider(): Provider
    {
        config(['services.google.redirect' => route('login.google.callback')]);

        return Socialite::driver('google');
    }

    /**
     * The local account: linked by Google ID, else by the (Google-verified)
     * email, else a new client account.
     */
    public function resolve(GoogleUser $google): User
    {
        $googleId = (string) $google->getId();

        $user = User::where('google_id', $googleId)->first()
            ?? $this->accounts->findOrCreate((string) $google->getEmail(), $google->getName() ?: null);

        if ($user->google_id !== $googleId) {
            $user->forceFill(['google_id' => $googleId])->save();
        }

        return $user;
    }

    /**
     * Google confirms the address belongs to the account holder.
     */
    public static function emailVerified(GoogleUser $google): bool
    {
        $raw = method_exists($google, 'getRaw') ? (array) $google->getRaw() : [];
        $verified = $raw['email_verified'] ?? $raw['verified_email'] ?? false;

        return filled($google->getEmail())
            && Str::contains((string) $google->getEmail(), '@')
            && ($verified === true || $verified === 'true' || $verified === 1);
    }
}
