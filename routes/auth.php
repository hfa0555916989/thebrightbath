<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginCodeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login - Custom URL for security
    Route::get('auth/access', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('auth/access', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login');

    // Login with a one-time code sent by email
    Route::get('auth/code', [LoginCodeController::class, 'create'])
        ->name('login.code');

    Route::post('auth/code', [LoginCodeController::class, 'send'])
        ->middleware('throttle:login-code');

    Route::get('auth/code/verify', [LoginCodeController::class, 'edit'])
        ->name('login.code.verify');

    Route::post('auth/code/verify', [LoginCodeController::class, 'verify'])
        ->middleware('throttle:login');

    // Continue with Google / Apple
    Route::get('auth/google', [SocialLoginController::class, 'googleRedirect'])
        ->name('login.google');

    Route::get('auth/google/callback', [SocialLoginController::class, 'googleCallback'])
        ->name('login.google.callback');

    Route::get('auth/apple', [SocialLoginController::class, 'appleRedirect'])
        ->name('login.apple');

    // Apple posts back cross-site (CSRF-exempt in bootstrap/app.php)
    Route::post('auth/apple/callback', [SocialLoginController::class, 'appleCallback'])
        ->name('login.apple.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
