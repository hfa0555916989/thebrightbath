<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymobWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Paymob Webhook Routes
|--------------------------------------------------------------------------
| These routes handle Paymob payment callbacks and webhooks
| They should be excluded from CSRF protection
*/

Route::prefix('paymob')->group(function () {
    // Transaction processed callback (webhook)
    Route::post('/callback', [PaymobWebhookController::class, 'callback'])->name('paymob.callback');
    
    // Transaction response (redirect after payment)
    Route::get('/response', [PaymobWebhookController::class, 'response'])->name('paymob.response');
});
