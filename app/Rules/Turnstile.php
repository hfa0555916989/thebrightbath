<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cloudflare Turnstile bot check (https://developers.cloudflare.com/turnstile/).
 * Validates the "cf-turnstile-response" field. When the keys are not configured
 * the check is off, so local/test environments keep working.
 */
class Turnstile implements ValidationRule
{
    /**
     * Run even when the field is missing from the request.
     */
    public bool $implicit = true;

    public static function enabled(): bool
    {
        return filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret_key'));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!self::enabled()) {
            return;
        }

        if (!is_string($value) || $value === '') {
            $fail('يرجى إكمال التحقق من أنك لست روبوت.');

            return;
        }

        try {
            $passed = Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.turnstile.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ])->json('success') === true;
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification request failed', ['error' => $e->getMessage()]);
            $passed = false;
        }

        if (!$passed) {
            $fail('فشل التحقق من أنك لست روبوت، يرجى المحاولة مرة أخرى.');
        }
    }
}
