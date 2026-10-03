<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Services\NeoleapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected NeoleapService $neoleap;

    public function __construct(NeoleapService $neoleap)
    {
        $this->neoleap = $neoleap;
    }

    /**
     * Initiate payment for a booking: send the customer to the bank's payment page.
     */
    public function initiatePayment(Request $request, Booking $booking)
    {
        // Check if the gateway is configured
        if (!$this->neoleap->isConfigured()) {
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
            $paymentUrl = $this->neoleap->createPaymentForBooking($booking->load('user'), $request->ips());

            return redirect()->away($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Result from the payment gateway (responseURL and errorURL).
     *
     * Two callers: the gateway server's notification (JSON), which must be acknowledged
     * or the gateway voids the payment, and the customer's browser redirect.
     */
    public function neoleapCallback(Request $request)
    {
        if ($request->isJson()) {
            $transaction = $this->neoleap->handleCallback($request->json()->all());

            // Acknowledge only payments we accepted; anything else gets voided by the gateway.
            if ($transaction && $transaction->status === 'success') {
                return response()->json([['status' => '1', 'result' => route('payment.neoleap.response')]]);
            }

            return response()->json([['status' => '2', 'result' => null]]);
        }

        $transaction = $this->neoleap->handleCallback($request->all());

        if ($transaction && $transaction->status === 'success') {
            return redirect()->route('payment.success', ['payment_id' => $transaction->order_id]);
        }

        return redirect()->route('payment.failed', array_filter([
            'error' => $transaction?->error_message,
        ]));
    }

    /**
     * Payment success page
     */
    public function paymentSuccess(Request $request)
    {
        $paymentId = $request->get('payment_id');

        if ($paymentId) {
            $transaction = PaymentTransaction::where('order_id', (string) $paymentId)->first();

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
     * Payment failed page
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
