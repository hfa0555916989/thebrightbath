<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\Turnstile;
use App\Services\Auth\AccountLogin;
use App\Services\Auth\LoginCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

/**
 * Sign in with a one-time code sent to the email address.
 */
class LoginCodeController extends Controller
{
    public function __construct(
        private readonly LoginCodeService $codes,
        private readonly AccountLogin $accounts,
    ) {}

    public function create(): View
    {
        return view('auth.login-code');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'cf-turnstile-response' => [new Turnstile],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
        ]);

        $email = Str::lower(trim($data['email']));

        try {
            $this->codes->send($email, $request->ip());
        } catch (Throwable $e) {
            Log::error('Failed to send login code: '.$e->getMessage());

            return back()->withInput()->withErrors(['email' => 'تعذر إرسال الرمز الآن. حاول مرة أخرى بعد قليل.']);
        }

        $request->session()->put('login_code_email', $email);

        return redirect()->route('login.code.verify')
            ->with('success', 'أرسلنا رمز الدخول إلى بريدك. صلاحيته '.LoginCodeService::TTL_MINUTES.' دقائق.');
    }

    public function edit(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('login_code_email');
        if (! $email) {
            return redirect()->route('login.code');
        }

        return view('auth.login-code-verify', ['email' => $email]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $email = $request->session()->get('login_code_email');
        if (! $email) {
            return redirect()->route('login.code')->with('error', 'انتهت الجلسة. أدخل بريدك واطلب رمزًا جديدًا.');
        }

        $data = $request->validate([
            'code' => ['required', 'string', 'max:20'],
        ], [
            'code.required' => 'أدخل الرمز الذي وصلك على البريد',
        ]);

        $user = $this->codes->verify($email, $data['code']);
        if (! $user) {
            return back()->withErrors(['code' => 'الرمز غير صحيح أو انتهت صلاحيته. يمكنك طلب رمز جديد.']);
        }

        $request->session()->forget('login_code_email');

        return $this->accounts->login($request, $user);
    }
}
