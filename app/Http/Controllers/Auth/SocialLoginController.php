<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AccountLogin;
use App\Services\Auth\AppleLogin;
use App\Services\Auth\GoogleLogin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;
use Throwable;

/**
 * "Continue with Google" and "Continue with Apple". Each button is hidden and
 * its routes return 404 until the provider's keys are set in .env.
 */
class SocialLoginController extends Controller
{
    public function __construct(
        private readonly AccountLogin $accounts,
    ) {}

    public function googleRedirect(GoogleLogin $google): SymfonyRedirect
    {
        abort_unless(GoogleLogin::enabled(), 404);

        return $google->provider()->redirect();
    }

    public function googleCallback(Request $request, GoogleLogin $google): RedirectResponse
    {
        abort_unless(GoogleLogin::enabled(), 404);

        try {
            $account = $google->provider()->user();
        } catch (Throwable $e) {
            Log::info('Google sign-in failed', ['error' => $e->getMessage()]);

            return $this->fail('تم إلغاء الدخول بحساب Google أو تعذر إكماله. حاول مرة أخرى.');
        }

        if (! GoogleLogin::emailVerified($account)) {
            return $this->fail('حساب Google هذا لا يحتوي على بريد إلكتروني موثّق.');
        }

        return $this->accounts->login($request, $google->resolve($account));
    }

    public function appleRedirect(AppleLogin $apple): RedirectResponse
    {
        abort_unless(AppleLogin::enabled(), 404);

        return redirect()->away($apple->authorizeUrl());
    }

    public function appleCallback(Request $request, AppleLogin $apple): RedirectResponse
    {
        abort_unless(AppleLogin::enabled(), 404);

        $nonce = $apple->nonceFrom((string) $request->input('state', ''));
        $code = (string) $request->input('code', '');
        if ($nonce === null || $code === '' || $request->filled('error')) {
            return $this->fail('تم إلغاء الدخول بحساب Apple أو تعذر إكماله. حاول مرة أخرى.');
        }

        $claims = $apple->claims($code, $nonce);
        if (! $claims) {
            return $this->fail('تعذر التحقق من الدخول بحساب Apple. حاول مرة أخرى.');
        }

        // Apple sends the name (JSON in "user") only on the first sign-in.
        $profile = json_decode((string) $request->input('user', ''), true);
        $name = trim(($profile['name']['firstName'] ?? '').' '.($profile['name']['lastName'] ?? ''));

        $user = $apple->resolve($claims, $name ?: null);
        if (! $user) {
            return $this->fail('لم يشارك Apple بريدًا إلكترونيًا موثّقًا. جرّب الدخول برمز البريد.');
        }

        return $this->accounts->login($request, $user);
    }

    private function fail(string $message): RedirectResponse
    {
        return redirect()->route('login')->with('error', $message);
    }
}
