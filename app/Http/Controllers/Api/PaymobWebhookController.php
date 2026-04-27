<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymobWebhookController extends Controller
{
    protected PaymobService $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    /**
     * Handle Paymob V2 callback (Transaction processed callback)
     */
    public function callback(Request $request)
    {
        Log::info('Paymob V2 webhook received', [
            'data' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        try {
            // Get the transaction data
            $data = $request->all();

            // V2 HMAC verification - check header first, then query param
            $receivedHmac = $request->header('HMAC') ?? $request->get('hmac');
            if ($receivedHmac) {
                if (!$this->paymobService->verifyHmac($data, $receivedHmac)) {
                    Log::warning('Paymob V2 webhook HMAC verification failed', [
                        'received_hmac' => substr($receivedHmac, 0, 20) . '...',
                    ]);
                    // Continue processing but log the warning
                }
            }

            // Process the callback (V2 format supported in PaymobService)
            $transaction = $this->paymobService->processCallback($data);

            if ($transaction) {
                Log::info('Paymob V2 webhook processed successfully', [
                    'transaction_id' => $transaction->transaction_id,
                    'status' => $transaction->status,
                ]);
            }

            return response()->json(['status' => 'received']);

        } catch (\Exception $e) {
            Log::error('Paymob V2 webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return 200 to prevent Paymob from retrying
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Handle Paymob V2 response (Redirect after payment from Unified Checkout)
     */
    public function response(Request $request)
    {
        Log::info('Paymob V2 response received', [
            'data' => $request->all(),
        ]);

        // V2 response parameters
        $success = filter_var($request->get('success'), FILTER_VALIDATE_BOOLEAN);
        $orderId = $request->get('order') ?? $request->get('merchant_order_id');
        $transactionId = $request->get('id') ?? $request->get('transaction_id');
        $intentionId = $request->get('intention_id');

        if ($success) {
            return redirect()->route('payment.success', [
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
                'intention_id' => $intentionId,
            ]);
        }

        return redirect()->route('payment.failed', [
            'order_id' => $orderId,
            'error' => $request->get('data_message') ?? $request->get('txn_response_code') ?? 'فشل في عملية الدفع',
        ]);
    }
}
