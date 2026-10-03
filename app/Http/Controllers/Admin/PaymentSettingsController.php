<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use App\Services\NeoleapService;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    /**
     * Display payment settings page
     */
    public function index()
    {
        $settings = PaymentSetting::where('gateway', 'neoleap')->first();

        // Get recent transactions
        $transactions = PaymentTransaction::with('payable')
            ->latest()
            ->take(10)
            ->get();

        // Statistics
        $stats = [
            'total' => PaymentTransaction::count(),
            'successful' => PaymentTransaction::successful()->count(),
            'pending' => PaymentTransaction::pending()->count(),
            'failed' => PaymentTransaction::failed()->count(),
            'total_amount' => PaymentTransaction::successful()->sum('amount'),
        ];

        return view('admin.payment-settings.index', compact('settings', 'transactions', 'stats'));
    }

    /**
     * Update Al Rajhi / Neoleap gateway settings.
     * Secret fields are only replaced when a new value is typed (they are never sent back to the page).
     */
    public function update(Request $request)
    {
        $request->validate([
            'tranportal_id' => 'nullable|string|max:100',
            'tranportal_password' => 'nullable|string|max:255',
            'resource_key' => 'nullable|string|size:32',
            'endpoint_url' => 'nullable|url:https|max:500',
            'is_sandbox' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'resource_key.size' => 'مفتاح Resource Key يجب أن يكون 32 حرفًا بالضبط',
            'endpoint_url.url' => 'رابط البوابة يجب أن يبدأ بـ https://',
        ]);

        $settings = PaymentSetting::firstOrNew(['gateway' => 'neoleap']);

        $settings->fill([
            'gateway' => 'neoleap',
            'currency' => 'SAR',
            'tranportal_id' => $request->filled('tranportal_id') ? trim($request->tranportal_id) : $settings->tranportal_id,
            'endpoint_url' => $request->filled('endpoint_url') ? trim($request->endpoint_url) : $settings->endpoint_url,
            'is_sandbox' => $request->boolean('is_sandbox'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('tranportal_password')) {
            $settings->tranportal_password = $request->tranportal_password;
        }
        if ($request->filled('resource_key')) {
            $settings->resource_key = $request->resource_key;
        }

        if ($settings->is_active && !$settings->isConfigured()) {
            return back()
                ->withInput($request->except(['tranportal_password', 'resource_key']))
                ->with('error', 'لا يمكن تفعيل البوابة قبل إدخال جميع بيانات الربط (Tranportal ID وكلمة المرور وResource Key ورابط البوابة)');
        }

        $settings->save();

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'تم حفظ إعدادات الدفع بنجاح');
    }

    /**
     * Test connection: ask the gateway for a payment page with the saved credentials.
     */
    public function testConnection(Request $request)
    {
        $settings = PaymentSetting::where('gateway', 'neoleap')->first();

        return response()->json(app(NeoleapService::class)->usingSettings($settings)->testConnection($request->ips()));
    }

    /**
     * View transactions
     */
    public function transactions(Request $request)
    {
        $query = PaymentTransaction::with('payable')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('order_id', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20);

        return view('admin.payment-settings.transactions', compact('transactions'));
    }
}
