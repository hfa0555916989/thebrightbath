<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower($request->email);
        
        // Check if user exists and email is verified
        $user = User::where('email', $email)->first();
        
        if ($user && !$user->email_verified_at && $user->role === 'client') {
            return back()->withErrors([
                'email' => 'يرجى تفعيل حسابك أولاً. تحقق من بريدك الإلكتروني.',
            ])->withInput()->with('show_resend', true)->with('unverified_email', $email);
        }

        $credentials = [
            'email' => $email,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('البيانات المدخلة غير صحيحة.'),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }
        
        if ($user->role === 'counselor') {
            return redirect()->intended(route('consultant.dashboard'));
        }

        return redirect()->intended(route('client.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
