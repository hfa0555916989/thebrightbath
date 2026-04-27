<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentsController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\AttemptController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\ConsultantController;

/*
|--------------------------------------------------------------------------
| Sitemap Routes
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-assessments.xml', [SitemapController::class, 'assessments'])->name('sitemap.assessments');
Route::get('/sitemap-consultations.xml', [SitemapController::class, 'consultations'])->name('sitemap.consultations');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Static Pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/vision-mission', function () {
    return view('pages.vision-mission');
})->name('vision-mission');

Route::get('/strategic-goals', function () {
    return view('pages.strategic-goals');
})->name('strategic-goals');

Route::get('/values', function () {
    return view('pages.values');
})->name('values');

Route::get('/services', function () {
    return view('pages.services');
})->name('services');

// Terms and Privacy
Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

// Assessments
Route::prefix('assessments')->group(function () {
    Route::get('/', [AssessmentsController::class, 'index'])->name('assessments.index');
    
    Route::middleware('auth')->group(function () {
        Route::get('/{slug}', [AssessmentsController::class, 'show'])->name('assessments.show');
        Route::post('/{slug}', [AssessmentsController::class, 'submit'])->name('assessments.submit');
        Route::get('/{slug}/result', [AssessmentsController::class, 'result'])->name('assessments.result');
    });
});

// Analysis Models (Public)
Route::prefix('analysis-models')->group(function () {
    Route::get('/', [App\Http\Controllers\AnalysisModelController::class, 'index'])->name('analysis-models.index');
    Route::get('/{model}', [App\Http\Controllers\AnalysisModelController::class, 'show'])->name('analysis-models.show');
    Route::get('/{model}/download', [App\Http\Controllers\AnalysisModelController::class, 'download'])->name('analysis-models.download');
});

// Career Book / Library
Route::prefix('library')->group(function () {
    Route::get('/', function () {
        $chapters = collect([
            (object) ['id' => 1, 'title' => 'مقدمة في الإرشاد المهني', 'slug' => 'introduction', 'order' => 1, 'is_free' => true, 'excerpt' => 'تعرف على أساسيات الإرشاد المهني وأهميته'],
            (object) ['id' => 2, 'title' => 'اكتشاف الذات والميول', 'slug' => 'self-discovery', 'order' => 2, 'is_free' => true, 'excerpt' => 'كيف تكتشف ميولك وقدراتك الحقيقية'],
            (object) ['id' => 3, 'title' => 'اختبارات الميول المهنية', 'slug' => 'assessments', 'order' => 3, 'is_free' => false, 'excerpt' => 'تعرف على أهم اختبارات الميول وكيفية الاستفادة منها'],
        ]);
        return view('book.index', compact('chapters'));
    })->name('career-book.index');
    
    Route::get('/{slug}', function ($slug) {
        $chaptersData = [
            'introduction' => ['title' => 'مقدمة في الإرشاد المهني', 'is_free' => true, 'content_html' => '<h2>ما هو الإرشاد المهني؟</h2><p>الإرشاد المهني هو عملية مساعدة الأفراد على اكتشاف ميولهم وقدراتهم.</p>'],
            'self-discovery' => ['title' => 'اكتشاف الذات والميول', 'is_free' => true, 'content_html' => '<h2>رحلة اكتشاف الذات</h2><p>تبدأ رحلة الإرشاد المهني الناجحة باكتشاف الذات.</p>'],
        ];
        
        $chapterData = $chaptersData[$slug] ?? null;
        $chapter = $chapterData ? (object) array_merge(['id' => 1, 'slug' => $slug, 'order' => 1], $chapterData) : (object) ['id' => 1, 'title' => 'فصل مقفل', 'slug' => $slug, 'order' => 1, 'is_free' => false, 'content_html' => null];
        $chapters = collect([]);
        return view('book.show', compact('chapter', 'chapters'));
    })->name('career-book.show');
});

// Contact
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function () {
    return redirect()->route('contact')->with('success', 'تم إرسال رسالتك بنجاح!');
})->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Consultations Routes
|--------------------------------------------------------------------------
*/

Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
Route::get('/consultations/{consultant}', [ConsultationController::class, 'show'])->name('consultations.show');

// Payment Result Pages (public - no auth required for redirect)
Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failed', [App\Http\Controllers\PaymentController::class, 'paymentFailed'])->name('payment.failed');

Route::middleware('auth')->group(function () {
    Route::post('/consultations/{consultant}/book', [ConsultationController::class, 'book'])->name('consultations.book');
    Route::get('/booking/{booking}/waiting-approval', [ConsultationController::class, 'waitingApproval'])->name('consultations.waiting-approval');
    Route::get('/booking/{booking}/payment', [ConsultationController::class, 'payment'])->name('consultations.payment');
    Route::post('/booking/{booking}/process', [ConsultationController::class, 'processPayment'])->name('consultations.process-payment');
    Route::get('/booking/{booking}/confirmation', [ConsultationController::class, 'confirmation'])->name('consultations.confirmation');
    Route::get('/my-bookings', [ConsultationController::class, 'myBookings'])->name('consultations.my-bookings');
    Route::delete('/booking/{booking}/cancel', [ConsultationController::class, 'cancel'])->name('consultations.cancel');
    
    // Payment Routes (Paymob)
    Route::post('/booking/{booking}/pay', [App\Http\Controllers\PaymentController::class, 'initiatePayment'])->name('payment.initiate');
    Route::get('/payment/status/{transactionId}', [App\Http\Controllers\PaymentController::class, 'getStatus'])->name('payment.status');
    
    // Client Dashboard
    Route::get('/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/my-results', [App\Http\Controllers\ClientController::class, 'results'])->name('client.results');
    Route::get('/my-results/{attempt}', [App\Http\Controllers\ClientController::class, 'showResult'])->name('client.result.show');
    Route::get('/my-sessions', [App\Http\Controllers\ClientController::class, 'sessions'])->name('client.sessions');
    Route::get('/my-invoices', [App\Http\Controllers\ClientController::class, 'invoices'])->name('client.invoices');
    Route::get('/profile', [App\Http\Controllers\ClientController::class, 'profile'])->name('client.profile');
    Route::put('/profile', [App\Http\Controllers\ClientController::class, 'updateProfile'])->name('client.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('control-panel')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Consultants Management
        Route::resource('consultants', ConsultantController::class);
        Route::get('consultants/{consultant}/schedule', [ConsultantController::class, 'schedule'])->name('consultants.schedule');
        Route::put('consultants/{consultant}/schedule', [ConsultantController::class, 'updateSchedule'])->name('consultants.update-schedule');
        Route::patch('consultants/{consultant}/toggle-active', [ConsultantController::class, 'toggleActive'])->name('consultants.toggle-active');
        
        // Assessments Management
        Route::get('assessments', function () {
            return view('admin.assessments.index');
        })->name('assessments.index');
        
        Route::get('assessments/create', function () {
            return view('admin.assessments.create');
        })->name('assessments.create');
        
        // Attempts Management
        Route::get('attempts', [AttemptController::class, 'index'])->name('attempts.index');
        Route::get('attempts/export', [AttemptController::class, 'export'])->name('attempts.export');
        Route::get('attempts/{id}', [AttemptController::class, 'show'])->name('attempts.show');
        Route::put('attempts/{id}', [AttemptController::class, 'update'])->name('attempts.update');
        Route::delete('attempts/{id}', [AttemptController::class, 'destroy'])->name('attempts.destroy');
        
        // Bookings Management
        Route::get('bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
        Route::put('bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'update'])->name('bookings.update');
        
        // Book Chapters
        Route::get('book-chapters', function () {
            return view('admin.book-chapters.index');
        })->name('book-chapters.index');
        
        Route::get('book-chapters/create', function () {
            return view('admin.book-chapters.create');
        })->name('book-chapters.create');
        
        // Security
        Route::get('security/logs', function () {
            return view('admin.security.logs');
        })->name('security.logs');
        
        Route::get('security/activity', function () {
            return view('admin.security.activity');
        })->name('security.activity');
        
        Route::get('security/blocked-ips', function () {
            return view('admin.security.blocked-ips');
        })->name('security.blocked-ips');
        
        // Profile & Settings
        Route::get('profile', function () {
            return view('admin.profile');
        })->name('profile');
        
        Route::put('profile', function (\Illuminate\Http\Request $request) {
            $user = auth()->user();
            $user->update($request->only(['name', 'email']));
            if ($request->filled('password')) {
                $user->update(['password' => bcrypt($request->password)]);
            }
            return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
        })->name('profile.update');
        
        Route::get('settings', function () {
            return view('admin.settings');
        })->name('settings');
        
        Route::put('settings', function (\Illuminate\Http\Request $request) {
            return back()->with('success', 'تم حفظ الإعدادات بنجاح');
        })->name('settings.update');
        
        // Users Management
        Route::get('users', function () {
            $users = \App\Models\User::latest()->paginate(20);
            return view('admin.users.index', compact('users'));
        })->name('users.index');
        
        // Logo Management
        Route::get('logo', [LogoController::class, 'index'])->name('logo.index');
        Route::post('logo', [LogoController::class, 'upload'])->name('logo.upload');
        
        // Analysis Models Management
        Route::resource('analysis-models', App\Http\Controllers\Admin\AnalysisModelController::class);
        Route::patch('analysis-models/{analysisModel}/toggle-active', [App\Http\Controllers\Admin\AnalysisModelController::class, 'toggleActive'])->name('analysis-models.toggle-active');
        Route::post('analysis-models/reorder', [App\Http\Controllers\Admin\AnalysisModelController::class, 'reorder'])->name('analysis-models.reorder');
        
        // Financial Reports
        Route::get('finance', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
        Route::get('finance/settings', [App\Http\Controllers\Admin\FinanceController::class, 'settings'])->name('finance.settings');
        Route::put('finance/settings', [App\Http\Controllers\Admin\FinanceController::class, 'updateSettings'])->name('finance.settings.update');
        
        // Payment Settings
        Route::prefix('payment-settings')->name('payment-settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PaymentSettingsController::class, 'index'])->name('index');
            Route::put('/', [App\Http\Controllers\Admin\PaymentSettingsController::class, 'update'])->name('update');
            Route::post('/test', [App\Http\Controllers\Admin\PaymentSettingsController::class, 'testConnection'])->name('test');
            Route::get('/transactions', [App\Http\Controllers\Admin\PaymentSettingsController::class, 'transactions'])->name('transactions');
        });
    });

/*
|--------------------------------------------------------------------------
| Consultant Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('consultant')->name('consultant.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ConsultantController::class, 'dashboard'])->name('dashboard');
    Route::get('/schedule', [App\Http\Controllers\ConsultantController::class, 'schedule'])->name('schedule');
    Route::put('/schedule', [App\Http\Controllers\ConsultantController::class, 'updateSchedule'])->name('schedule.update');
    Route::get('/earnings', [App\Http\Controllers\ConsultantController::class, 'earnings'])->name('earnings');
    Route::get('/sessions', [App\Http\Controllers\ConsultantController::class, 'sessions'])->name('sessions');
    Route::get('/profile', [App\Http\Controllers\ConsultantController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ConsultantController::class, 'updateProfile'])->name('profile.update');
    Route::get('/client/{booking}', [App\Http\Controllers\ConsultantController::class, 'clientDetails'])->name('client-details');
    
    // Booking approval routes
    Route::get('/pending-requests', [App\Http\Controllers\ConsultantController::class, 'pendingRequests'])->name('pending-requests');
    Route::post('/booking/{booking}/approve', [App\Http\Controllers\ConsultantController::class, 'approveBooking'])->name('booking.approve');
    Route::post('/booking/{booking}/reject', [App\Http\Controllers\ConsultantController::class, 'rejectBooking'])->name('booking.reject');
});

/*
|--------------------------------------------------------------------------
| Video Call Routes (WebRTC)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('video-call')->name('video-call.')->group(function () {
    Route::get('/{booking}/join', [App\Http\Controllers\VideoCallController::class, 'join'])->name('join');
    Route::post('/{booking}/end', [App\Http\Controllers\VideoCallController::class, 'end'])->name('end');
    Route::post('/{videoCall}/signal', [App\Http\Controllers\VideoCallController::class, 'sendSignal'])->name('signal');
    Route::get('/{videoCall}/signals', [App\Http\Controllers\VideoCallController::class, 'getSignals'])->name('get-signals');
    Route::get('/{videoCall}/status', [App\Http\Controllers\VideoCallController::class, 'checkStatus'])->name('status');
    // Chat & Files
    Route::post('/{videoCall}/message', [App\Http\Controllers\VideoCallController::class, 'sendMessage'])->name('send-message');
    Route::get('/{videoCall}/messages', [App\Http\Controllers\VideoCallController::class, 'getMessages'])->name('get-messages');
    Route::post('/{videoCall}/upload', [App\Http\Controllers\VideoCallController::class, 'uploadFile'])->name('upload-file');
});

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

Route::get('/verify-email/{token}', [App\Http\Controllers\Auth\RegisteredUserController::class, 'verifyEmail'])
    ->name('verification.verify');

Route::post('/resend-verification', [App\Http\Controllers\Auth\RegisteredUserController::class, 'resendVerification'])
    ->name('verification.resend');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.forgot');

Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
