<?php

namespace App\Services\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

/**
 * "Continue with Apple": Apple's web OAuth flow with a Services ID.
 *
 * Apple posts back cross-site (form_post), so the session cookie is not sent;
 * the nonce travels in an encrypted, short-lived state value instead.
 *
 * Keys (config/services.php): APPLE_CLIENT_ID (the Services ID), APPLE_TEAM_ID,
 * APPLE_KEY_ID and APPLE_PRIVATE_KEY (the .p8 file's contents).
 */
class AppleLogin
{
    private const STATE_TTL_SECONDS = 600;

    public function __construct(
        private readonly IdTokens $tokens,
        private readonly AccountLogin $accounts,
    ) {}

    public static function enabled(): bool
    {
        return filled(config('services.apple.client_id'))
            && filled(config('services.apple.team_id'))
            && filled(config('services.apple.key_id'))
            && self::privateKey() !== '';
    }

    public function authorizeUrl(): string
    {
        $nonce = Str::random(40);
        $state = Crypt::encryptString((string) json_encode(['n' => $nonce, 't' => time()]));

        return 'https://appleid.apple.com/auth/authorize?'.http_build_query([
            'response_type' => 'code',
            'response_mode' => 'form_post',
            'client_id' => (string) config('services.apple.client_id'),
            'redirect_uri' => route('login.apple.callback'),
            'scope' => 'name email',
            'state' => $state,
            'nonce' => hash('sha256', $nonce),
        ]);
    }

    /** The raw nonce from a valid, recent state; null otherwise. */
    public function nonceFrom(string $state): ?string
    {
        try {
            $data = json_decode(Crypt::decryptString($state), true);
        } catch (Throwable) {
            return null;
        }

        return is_array($data) && isset($data['n'], $data['t']) && time() - (int) $data['t'] <= self::STATE_TTL_SECONDS
            ? (string) $data['n'] : null;
    }

    /**
     * Swap the authorization code for tokens and verify the ID token.
     *
     * @return array<string, mixed>|null
     */
    public function claims(string $code, string $nonce): ?array
    {
        $clientId = (string) config('services.apple.client_id');

        try {
            $response = Http::asForm()->timeout(15)->post('https://appleid.apple.com/auth/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => route('login.apple.callback'),
                'client_id' => $clientId,
                'client_secret' => $this->clientSecret(),
            ]);
        } catch (Throwable) {
            return null;
        }

        $idToken = (string) $response->json('id_token', '');
        if (! $response->successful() || $idToken === '') {
            return null;
        }

        return $this->tokens->verifyApple($idToken, $clientId, $nonce);
    }

    /**
     * Apple's client secret: a short-lived JWT signed with our .p8 key.
     */
    public function clientSecret(): string
    {
        $now = time();

        return JWT::encode([
            'iss' => (string) config('services.apple.team_id'),
            'iat' => $now,
            'exp' => $now + 300,
            'aud' => 'https://appleid.apple.com',
            'sub' => (string) config('services.apple.client_id'),
        ], self::privateKey(), 'ES256', (string) config('services.apple.key_id'));
    }

    /**
     * Linked by Apple ID, else by the verified email, else a new client
     * account. null when Apple did not share a verified email.
     *
     * @param  array<string, mixed>  $claims
     */
    public function resolve(array $claims, ?string $name): ?User
    {
        $appleId = (string) $claims['sub'];

        $user = User::where('apple_id', $appleId)->first();
        if ($user) {
            return $user;
        }

        if (! IdTokens::emailVerified($claims)) {
            return null;
        }

        $user = $this->accounts->findOrCreate((string) $claims['email'], $name);
        $user->forceFill(['apple_id' => $appleId])->save();

        return $user;
    }

    /**
     * The .p8 key. Hosting panels often store it on one line, with literal
     * "\n" for the line breaks, so those are restored.
     */
    private static function privateKey(): string
    {
        return trim(str_replace('\n', "\n", (string) config('services.apple.private_key')));
    }
}
