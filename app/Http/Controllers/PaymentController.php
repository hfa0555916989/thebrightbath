<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymobService $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    /**
     * Initiate payment for a booking
     */
    public function initiatePayment(Request $request, Booking $booking)
    {
        // Check if Paymob is configured
        if (!$this->paymobService->isConfigured()) {
            return back()->with('error', 'بوابة الدفع غير متاحة حالياً');
        }

        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'غير مصرح');
        }

        // Check if booking is already paid
        if ($booking->payment_status === 'paid') {
            return back()->with('error', 'تم دفع هذا الحجز مسبقاً');
        }

        try {
            $paymentData = $this->paymobService->createPaymentForBooking($booking);

            // Redirect to payment iframe or return iframe URL
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'iframe_url' => $paymentData['iframe_url'],
                    'transaction_id' => $paymentData['transaction_id'],
                ]);
            }

            // For web requests, redirect to a payment page
            return view('payment.iframe', [
                'iframeUrl' => $paymentData['iframe_url'],
                'booking' => $booking,
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
            ]);

        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Payment success callback (redirect from iframe)
     */
    public function paymentSuccess(Request $request)
    {
        $transactionId = $request->get('merchant_order_id');
        
        if ($transactionId) {
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();
            
            if ($transaction && $transaction->status === 'success') {
                return view('payment.success', [
                    'transaction' => $transaction,
                ]);
            }
        }

        // If no valid transaction, show pending message
        return view('payment.pending');
    }

    /**
     * Payment failed callback
     */
    public function paymentFailed(Request $request)
    {
        return view('payment.failed');
    }

    /**
     * Get payment status
     */
    public function getStatus(string $transactionId)
    {
        $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'المعاملة غير موجودة',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $transaction->status,
            'status_label' => $transaction->getStatusLabel(),
        ]);
    }
}
