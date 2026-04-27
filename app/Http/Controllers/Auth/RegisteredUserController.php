<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
            'g-recaptcha-response' => ['required'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'phone.required' => 'رقم الجوال مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
            'g-recaptcha-response.required' => 'يرجى التحقق من أنك لست روبوت',
        ]);

        // Verify reCAPTCHA
        $recaptchaSecret = config('services.recaptcha.secret_key', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json('success')) {
            return back()->withErrors(['g-recaptcha-response' => 'فشل التحقق، يرجى المحاولة مرة أخرى'])->withInput();
        }

        // Generate verification token
        $verificationToken = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'verification_token' => $verificationToken,
            'verification_token_expires_at' => now()->addHours(24),
        ]);

        // Send welcome email with verification link
        try {
            $verificationUrl = url('/verify-email/' . $verificationToken);
            Mail::to($user->email)->send(new WelcomeEmail($user, $verificationUrl));
        } catch (\Exception $e) {
            // Log the error but don't block registration
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect(route('login'))->with('success', 'تم إنشاء حسابك بنجاح! يرجى التحقق من بريدك الإلكتروني لتفعيل الحساب.');
    }

    /**
     * Verify email address
     */
    public function verifyEmail(string $token): RedirectResponse
    {
        $user = User::where('verification_token', $token)
            ->where('verification_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect(route('login'))->with('error', 'رابط التفعيل غير صالح أو منتهي الصلاحية.');
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
            'verification_token_expires_at' => null,
        ]);

        return redirect(route('login'))->with('success', 'تم تفعيل حسابك بنجاح! يمكنك الآن تسجيل الدخول.');
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();

        if (!$user) {
            return back()->with('error', 'هذا البريد الإلكتروني مُفعّل مسبقاً.');
        }

        // Generate new token
        $verificationToken = Str::random(64);
        $user->update([
            'verification_token' => $verificationToken,
            'verification_token_expires_at' => now()->addHours(24),
        ]);

        try {
            $verificationUrl = url('/verify-email/' . $verificationToken);
            Mail::to($user->email)->send(new WelcomeEmail($user, $verificationUrl));
        } catch (\Exception $e) {
            \Log::error('Failed to resend verification email: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في إرسال البريد. يرجى المحاولة لاحقاً.');
        }

        return back()->with('success', 'تم إرسال رابط التفعيل إلى بريدك الإلكتروني.');
    }
}
