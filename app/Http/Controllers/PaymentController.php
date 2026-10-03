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

        // Only bookings the consultant has approved can be paid
        if ($booking->status !== 'approved') {
            return back()->with('error', 'لا يمكن الدفع لهذا الحجز قبل موافقة المستشار');
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
        // Paymob redirects with its transaction "id" (stored on our record once the webhook
        // has landed) and our special reference as "merchant_order_id".
        $transactionId = $request->get('id');
        $reference = $request->get('merchant_order_id');

        if ($transactionId || $reference) {
            $transaction = PaymentTransaction::when($transactionId, fn ($q) => $q->where('transaction_id', (string) $transactionId))
                ->when(!$transactionId, fn ($q) => $q->whereJsonContains('gateway_response->special_reference', (string) $reference))
                ->first();

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

        $ownsTransaction = $transaction
            && $transaction->payable_type === Booking::class
            && Booking::whereKey($transaction->payable_id)->where('user_id', auth()->id())->exists();

        if (!$ownsTransaction) {
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
