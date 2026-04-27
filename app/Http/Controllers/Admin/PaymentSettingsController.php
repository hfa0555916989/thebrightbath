<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    /**
     * Display payment settings page
     */
    public function index()
    {
        $settings = PaymentSetting::where('gateway', 'paymob')->first();
        
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
     * Update payment settings (V2 Intention API)
     */
    public function update(Request $request)
    {
        $request->validate([
            'secret_key' => 'nullable|string|max:500',
            'public_key' => 'nullable|string|max:500',
            'integration_id' => 'nullable|string|max:200',
            'hmac_secret' => 'nullable|string|max:500',
            'currency' => 'required|string|in:SAR,EGP,AED,USD',
            'is_sandbox' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $settings = PaymentSetting::firstOrNew(['gateway' => 'paymob']);
        
        // Only update non-empty values to preserve existing ones
        $data = [
            'gateway' => 'paymob',
            'currency' => $request->currency,
            'is_sandbox' => $request->boolean('is_sandbox'),
            'is_active' => $request->boolean('is_active'),
        ];

        // Update credentials only if provided (not empty)
        if ($request->filled('secret_key')) {
            $data['secret_key'] = $request->secret_key;
        }
        if ($request->filled('public_key')) {
            $data['public_key'] = $request->public_key;
        }
        if ($request->filled('integration_id')) {
            $data['integration_id'] = $request->integration_id;
        }
        if ($request->filled('hmac_secret')) {
            $data['hmac_secret'] = $request->hmac_secret;
        }

        $settings->fill($data);
        $settings->save();

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'تم حفظ إعدادات الدفع بنجاح');
    }

    /**
     * Test connection (V2 Intention API)
     */
    public function testConnection()
    {
        $settings = PaymentSetting::getPaymob();

        if (!$settings || !$settings->secret_key) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم إدخال Secret Key',
            ]);
        }

        try {
            $service = new \App\Services\PaymobService();
            $result = $service->testConnection();

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage(),
            ]);
        }
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
