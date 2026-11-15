<?php

namespace App\Services\Auth;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Verifies an identity token from "Sign in with Apple": signature (Apple's
 * published keys), issuer, audience (our Services ID), expiry and nonce.
 */
class IdTokens
{
    private const KEYS_URL = 'https://appleid.apple.com/auth/keys';

    private const ISSUER = 'https://appleid.apple.com';

    /**
     * The token's claims, or null when it is not valid for us.
     *
     * @return array<string, mixed>|null
     */
    public function verifyApple(string $token, string $audience, string $nonce): ?array
    {
        if ($token === '' || $audience === '') {
            return null;
        }

        try {
            JWT::$leeway = 60;
            $claims = (array) JWT::decode($token, JWK::parseKeySet($this->keys(), 'RS256'));
        } catch (Throwable $e) {
            return $this->reject($e->getMessage());
        }

        return match (true) {
            ($claims['iss'] ?? null) !== self::ISSUER => $this->reject('issuer'),
            ! in_array($audience, (array) ($claims['aud'] ?? []), true) => $this->reject('audience'),
            blank($claims['sub'] ?? null) => $this->reject('subject'),
            // We send the SHA-256 of the nonce; the token must carry it back (replay protection).
            ! hash_equals(hash('sha256', $nonce), (string) ($claims['nonce'] ?? '')) => $this->reject('nonce'),
            default => $claims,
        };
    }

    private function reject(string $reason): null
    {
        Log::warning('Apple sign-in: ID token rejected', ['reason' => $reason]);

        return null;
    }

    /**
     * @param  array<string, mixed>  $claims
     */
    public static function emailVerified(array $claims): bool
    {
        $verified = $claims['email_verified'] ?? false;

        return filled($claims['email'] ?? null) && ($verified === true || $verified === 'true');
    }

    /**
     * @return array<string, mixed>
     */
    private function keys(): array
    {
        return Cache::remember('apple-id-token-keys', 3600 * 6, fn () => Http::timeout(10)->acceptJson()
            ->get(self::KEYS_URL)->throw()->json());
    }
}
