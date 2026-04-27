<?php

namespace App\Services;

use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymobService
{
    protected ?PaymentSetting $settings;
    
    // Paymob KSA Base URL for V2 Intention API
    protected string $baseUrl = 'https://ksa.paymob.com/v1/intention';

    public function __construct()
    {
        $this->settings = PaymentSetting::getPaymob();
    }

    /**
     * Check if Paymob is configured and active
     */
    public function isConfigured(): bool
    {
        return $this->settings && $this->settings->isConfigured() && $this->settings->is_active;
    }

    /**
     * Create Payment Intention (V2 Unified API - Single Step)
     * 
     * @param float $amount Amount in SAR (will be converted to halalas)
     * @param array $billingData Customer billing information
     * @param string|null $specialReference Merchant order reference
     * @param array $items Order items
     * @param array $paymentMethods Allowed payment methods
     * @return array
     */
    public function createIntention(
        float $amount,
        array $billingData = [],
        ?string $specialReference = null,
        array $items = [],
        array $paymentMethods = []
    ): array {
        if (!$this->settings || !$this->settings->secret_key) {
            throw new Exception('Paymob Secret Key not configured');
        }

        $currency = $this->settings->currency ?? 'SAR';
        
        // Convert to smallest unit (halalas for SAR, cents for USD, etc.)
        $amountCents = (int) round($amount * 100);

        // Build request payload
        $payload = [
            'amount' => $amountCents,
            'currency' => $currency,
            'payment_methods' => $paymentMethods ?: $this->getDefaultPaymentMethods(),
            'billing_data' => $this->formatBillingData($billingData),
            'special_reference' => $specialReference ?? 'ORDER-' . time() . '-' . uniqid(),
            'notification_url' => url('/api/paymob/callback'),
            'redirection_url' => url('/payment/success'),
        ];

        // Add items if provided
        if (!empty($items)) {
            $payload['items'] = $items;
        }

        // Add integration IDs if set
        if ($this->settings->integration_id) {
            $integrationIds = array_map('trim', explode(',', $this->settings->integration_id));
            $payload['integrations'] = array_map('intval', $integrationIds);
        }

        try {
            Log::info('Paymob V2: Creating intention', [
                'amount' => $amount,
                'amount_cents' => $amountCents,
                'currency' => $currency,
                'special_reference' => $payload['special_reference'],
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->settings->secret_key,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Paymob V2: Intention created successfully', [
                    'intention_id' => $data['id'] ?? null,
                    'client_secret' => substr($data['client_secret'] ?? '', 0, 20) . '...',
                ]);

                return [
                    'success' => true,
                    'intention_id' => $data['id'] ?? null,
                    'client_secret' => $data['client_secret'] ?? null,
                    'payment_keys' => $data['payment_keys'] ?? [],
                    'checkout_url' => $this->buildCheckoutUrl($data['client_secret'] ?? ''),
                    'amount' => $amount,
                    'amount_cents' => $amountCents,
                    'currency' => $currency,
                    'special_reference' => $payload['special_reference'],
                    'raw_response' => $data,
                ];
            }

            $errorData = $response->json();
            Log::error('Paymob V2: Intention creation failed', [
                'status' => $response->status(),
                'response' => $errorData,
            ]);

            throw new Exception($errorData['message'] ?? $errorData['detail'] ?? 'فشل في إنشاء طلب الدفع');

        } catch (Exception $e) {
            Log::error('Paymob V2: Intention creation error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create payment for a booking using V2 Intention API
     */
    public function createPaymentForBooking(Booking $booking): array
    {
        if (!$this->isConfigured()) {
            throw new Exception('بوابة الدفع غير مفعلة');
        }

        $user = $booking->user;
        $consultant = $booking->consultant;
        
        // Get amount from booking
        $amount = $booking->total_amount ?? $consultant->hourly_rate ?? 0;
        
        if ($amount <= 0) {
            throw new Exception('مبلغ الدفع غير صحيح');
        }

        // Prepare billing data
        $billingData = [
            'first_name' => $user->name ?? 'Customer',
            'last_name' => 'User',
            'email' => $user->email ?? 'customer@example.com',
            'phone_number' => $this->formatPhoneNumber($user->phone),
        ];

        // Prepare items
        $items = [
            [
                'name' => 'استشارة مع ' . ($consultant->user->name ?? 'المستشار'),
                'amount' => (int) round($amount * 100),
                'description' => 'حجز استشارة - ' . $booking->scheduled_at?->format('Y-m-d H:i'),
                'quantity' => 1,
            ]
        ];

        // Special reference for tracking
        $specialReference = 'BOOKING-' . $booking->id . '-' . time();

        // Create intention
        $intentionResult = $this->createIntention(
            $amount,
            $billingData,
            $specialReference,
            $items
        );

        // Create transaction record
        $transaction = PaymentTransaction::create([
            'transaction_id' => $intentionResult['intention_id'] ?? ('INT_' . uniqid()),
            'order_id' => $intentionResult['intention_id'],
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => $amount,
            'amount_cents' => $intentionResult['amount_cents'],
            'currency' => $intentionResult['currency'],
            'status' => 'pending',
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'gateway_response' => [
                'intention_id' => $intentionResult['intention_id'],
                'client_secret' => $intentionResult['client_secret'],
                'special_reference' => $intentionResult['special_reference'],
            ],
        ]);

        // Update booking with payment reference
        $booking->update([
            'payment_reference' => $transaction->transaction_id,
        ]);

        return [
            'checkout_url' => $intentionResult['checkout_url'],
            'client_secret' => $intentionResult['client_secret'],
            'intention_id' => $intentionResult['intention_id'],
            'transaction_id' => $transaction->transaction_id,
            'amount' => $amount,
            'currency' => $intentionResult['currency'],
            // Keep iframe_url for backward compatibility
            'iframe_url' => $intentionResult['checkout_url'],
        ];
    }

    /**
     * Build checkout URL using client_secret
     */
    protected function buildCheckoutUrl(string $clientSecret): string
    {
        $publicKey = $this->settings->public_key ?? $this->settings->api_key;
        
        // Paymob KSA Unified Checkout URL
        return "https://ksa.paymob.com/unifiedcheckout/?publicKey={$publicKey}&clientSecret={$clientSecret}";
    }

    /**
     * Get default payment methods
     */
    protected function getDefaultPaymentMethods(): array
    {
        $methods = $this->settings->supported_methods ?? [];
        
        if (empty($methods)) {
            // Default payment methods for KSA
            return ['CARD', 'MADA'];
        }
        
        return $methods;
    }

    /**
     * Format billing data for Paymob
     */
    protected function formatBillingData(array $data): array
    {
        return [
            'first_name' => $data['first_name'] ?? 'Customer',
            'last_name' => $data['last_name'] ?? 'User',
            'email' => $data['email'] ?? 'customer@example.com',
            'phone_number' => $this->formatPhoneNumber($data['phone_number'] ?? $data['phone'] ?? ''),
            'apartment' => $data['apartment'] ?? 'NA',
            'floor' => $data['floor'] ?? 'NA',
            'street' => $data['street'] ?? 'NA',
            'building' => $data['building'] ?? 'NA',
            'shipping_method' => $data['shipping_method'] ?? 'NA',
            'postal_code' => $data['postal_code'] ?? 'NA',
            'city' => $data['city'] ?? 'NA',
            'state' => $data['state'] ?? 'NA',
            'country' => $data['country'] ?? 'SA',
        ];
    }

    /**
     * Format phone number for Saudi Arabia
     */
    protected function formatPhoneNumber(?string $phone): string
    {
        if (empty($phone)) {
            return '+966500000000';
        }
        
        // Remove spaces and dashes
        $phone = preg_replace('/[\s\-]/', '', $phone);
        
        // If starts with 05, convert to +9665
        if (preg_match('/^05/', $phone)) {
            return '+966' . substr($phone, 1);
        }
        
        // If starts with 5 (without 0), add +966
        if (preg_match('/^5\d{8}$/', $phone)) {
            return '+966' . $phone;
        }
        
        // If doesn't have +, add it
        if (!str_starts_with($phone, '+')) {
            return '+' . $phone;
        }
        
        return $phone;
    }

    /**
     * Verify HMAC from webhook (V2 format)
     */
    public function verifyHmac(array $data, string $receivedHmac): bool
    {
        if (!$this->settings || !$this->settings->hmac_secret) {
            Log::warning('Paymob HMAC secret not configured');
            return false;
        }

        // V2 HMAC calculation - fields in specific order
        $hmacFields = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order.id',
            'owner',
            'pending',
            'source_data.pan',
            'source_data.sub_type',
            'source_data.type',
            'success',
        ];

        $concatenatedString = '';
        foreach ($hmacFields as $field) {
            $value = data_get($data, str_replace('.', '_', $field), '');
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $concatenatedString .= $value;
        }

        $calculatedHmac = hash_hmac('sha512', $concatenatedString, $this->settings->hmac_secret);

        return hash_equals($calculatedHmac, $receivedHmac);
    }

    /**
     * Process webhook callback (V2 format)
     */
    public function processCallback(array $data): ?PaymentTransaction
    {
        // V2 callback structure
        $obj = $data['obj'] ?? $data;
        
        $intentionId = data_get($obj, 'order.id') ?? data_get($obj, 'id');
        $transactionId = data_get($obj, 'id');
        $success = data_get($obj, 'success', false);
        $pending = data_get($obj, 'pending', false);
        $specialReference = data_get($obj, 'order.merchant_order_id') ?? data_get($obj, 'special_reference');

        if (!$intentionId && !$specialReference) {
            Log::warning('Paymob V2 callback: No order ID or special reference found', $data);
            return null;
        }

        // Find transaction by intention ID or special reference
        $transaction = PaymentTransaction::where('order_id', $intentionId)
            ->orWhere('transaction_id', $intentionId)
            ->first();

        if (!$transaction && $specialReference) {
            // Try to find by special reference in gateway_response
            $transaction = PaymentTransaction::whereJsonContains('gateway_response->special_reference', $specialReference)->first();
        }

        if (!$transaction) {
            Log::warning('Paymob V2 callback: Transaction not found', [
                'intention_id' => $intentionId,
                'special_reference' => $specialReference,
            ]);
            return null;
        }

        // Update transaction
        $transaction->update([
            'transaction_id' => $transactionId ?? $transaction->transaction_id,
            'payment_method' => data_get($obj, 'source_data.type', 'card'),
            'card_type' => data_get($obj, 'source_data.sub_type'),
            'card_last_four' => data_get($obj, 'source_data.pan'),
            'gateway_response' => array_merge($transaction->gateway_response ?? [], ['callback' => $data]),
        ]);

        if ($success === true) {
            $transaction->markAsSuccessful($data);

            // Update booking status
            if ($transaction->payable_type === Booking::class) {
                $booking = Booking::find($transaction->payable_id);
                if ($booking) {
                    $booking->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'payment_method' => 'paymob',
                        'payment_data' => [
                            'transaction_id' => $transactionId,
                            'intention_id' => $intentionId,
                            'paid_at' => now()->toISOString(),
                        ],
                    ]);
                }
            }

            Log::info('Paymob V2: Payment successful', [
                'transaction_id' => $transactionId,
                'intention_id' => $intentionId,
            ]);

        } elseif ($pending === true) {
            Log::info('Paymob V2: Payment pending', [
                'transaction_id' => $transactionId,
                'intention_id' => $intentionId,
            ]);

        } else {
            $errorMessage = data_get($obj, 'data.message') 
                ?? data_get($obj, 'txn_response_code') 
                ?? 'فشل في عملية الدفع';
            $transaction->markAsFailed($errorMessage, $data);

            Log::warning('Paymob V2: Payment failed', [
                'transaction_id' => $transactionId,
                'intention_id' => $intentionId,
                'error' => $errorMessage,
            ]);
        }

        return $transaction;
    }

    /**
     * Test connection to Paymob (V2)
     */
    public function testConnection(): array
    {
        if (!$this->settings || !$this->settings->secret_key) {
            return [
                'success' => false,
                'message' => 'Secret Key غير مُعد',
            ];
        }

        try {
            // Try to create a minimal test intention
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->settings->secret_key,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($this->baseUrl, [
                'amount' => 100, // 1 SAR in halalas
                'currency' => $this->settings->currency ?? 'SAR',
                'payment_methods' => ['CARD'],
                'billing_data' => $this->formatBillingData([]),
                'special_reference' => 'TEST-' . time(),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'تم الاتصال بنجاح! Paymob V2 Intention API يعمل.',
                ];
            }

            $error = $response->json();
            return [
                'success' => false,
                'message' => 'فشل الاتصال: ' . ($error['message'] ?? $error['detail'] ?? 'خطأ غير معروف'),
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'خطأ في الاتصال: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get settings
     */
    public function getSettings(): ?PaymentSetting
    {
        return $this->settings;
    }
}
