<?php

namespace App\Services;

use App\Mail\BookingConfirmation;
use App\Mail\ConsultantEarnings;
use App\Mail\InvoiceEmail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Confirms a booking once the gateway has reported a verified, successful payment.
 * This is the only place a booking becomes paid/confirmed.
 */
class BookingPaymentService
{
    public function confirm(Booking $booking, PaymentTransaction $transaction): void
    {
        $payment = DB::transaction(function () use ($booking, $transaction) {
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->first();

            // Gateway retries the same callback: already handled.
            if ($booking->payment_status === 'paid') {
                return null;
            }

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'status' => 'completed',
                'payment_method' => $transaction->payment_method ?? 'card',
                'gateway' => 'paymob',
                'gateway_transaction_id' => $transaction->transaction_id,
                'card_brand' => $transaction->card_type,
                'card_last_four' => $transaction->card_last_four,
                'completed_at' => now(),
            ]);

            // Money arrived for a booking that is no longer awaiting payment
            // (e.g. cancelled meanwhile): record it, but don't confirm the session.
            if ($booking->status !== 'approved') {
                $booking->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => 'paymob',
                    'transaction_id' => $transaction->transaction_id,
                ]);

                Log::critical('Paid booking is not awaiting payment — needs manual review/refund', [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'transaction_id' => $transaction->transaction_id,
                ]);

                return null;
            }

            $commissionRate = $booking->consultant->commission_rate ?? 20; // Default 20%
            $consultantEarnings = round($booking->price * (1 - ($commissionRate / 100)), 2);

            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'paid_at' => now(),
                'payment_method' => 'paymob',
                'transaction_id' => $transaction->transaction_id,
                'consultant_earnings' => $consultantEarnings,
                'admin_earnings' => $booking->price - $consultantEarnings,
            ]);

            $booking->consultant->increment('total_sessions');

            return $payment;
        });

        if ($payment) {
            $this->sendNotifications($payment);
        }
    }

    private function sendNotifications(Payment $payment): void
    {
        $booking = $payment->booking->load(['consultant.user', 'user']);

        try {
            Mail::to($booking->user->email)->send(new BookingConfirmation($booking, 'client'));
            Mail::to($booking->consultant->user->email)->send(new BookingConfirmation($booking, 'consultant'));
            Mail::to($booking->user->email)->send(new InvoiceEmail($payment));
            Mail::to($booking->consultant->user->email)
                ->send(new ConsultantEarnings($booking->consultant, $booking, (float) $booking->consultant_earnings));
        } catch (\Exception $e) {
            // Log error but don't fail the payment process
            Log::error('Failed to send booking notification emails: '.$e->getMessage());
        }
    }
}
