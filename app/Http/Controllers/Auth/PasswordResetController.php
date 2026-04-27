<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetEmail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
        ]);

        $user = User::where('email', strtolower($request->email))->first();

        // Always show success message to prevent email enumeration
        if (!$user) {
            return back()->with('success', 'إذا كان البريد الإلكتروني مسجلاً لدينا، سيتم إرسال رابط استعادة كلمة المرور.');
        }

        // Generate reset token
        $resetToken = Str::random(64);
        $user->update([
            'password_reset_token' => $resetToken,
            'password_reset_expires_at' => now()->addHour(),
        ]);

        // Send email
        try {
            $resetUrl = url('/reset-password/' . $resetToken);
            Mail::to($user->email)->send(new PasswordResetEmail($user, $resetUrl));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في إرسال البريد. يرجى المحاولة لاحقاً.');
        }

        return back()->with('success', 'تم إرسال رابط استعادة كلمة المرور إلى بريدك الإلكتروني.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm(string $token): View|RedirectResponse
    {
        $user = User::where('password_reset_token', $token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect(route('password.forgot'))
                ->with('error', 'رابط استعادة كلمة المرور غير صالح أو منتهي الصلاحية.');
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $user->email]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
        ]);

        $user = User::where('email', strtolower($request->email))
            ->where('password_reset_token', $request->token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->with('error', 'رابط استعادة كلمة المرور غير صالح أو منتهي الصلاحية.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
            'password_changed_at' => now(),
        ]);

        return redirect(route('login'))
            ->with('success', 'تم تغيير كلمة المرور بنجاح! يمكنك الآن تسجيل الدخول.');
    }
}



