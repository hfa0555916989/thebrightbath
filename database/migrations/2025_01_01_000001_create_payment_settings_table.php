<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gateway')->default('paymob'); // paymob, stripe, tap, etc.
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('integration_id')->nullable(); // Paymob Integration ID
            $table->string('iframe_id')->nullable(); // Paymob Iframe ID
            $table->string('hmac_secret')->nullable(); // Paymob HMAC Secret
            $table->string('merchant_id')->nullable(); // For other gateways
            $table->string('currency')->default('SAR');
            $table->boolean('is_sandbox')->default(true); // Test mode
            $table->boolean('is_active')->default(false);
            $table->json('supported_methods')->nullable(); // card, wallet, installment
            $table->json('extra_settings')->nullable(); // Any additional settings
            $table->timestamps();
        });

        // Add payment columns to bookings if not exists
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('bookings', 'payment_data')) {
                $table->json('payment_data')->nullable()->after('payment_method');
            }
        });

        // Create payment transactions table
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // Paymob transaction ID
            $table->string('order_id')->nullable(); // Paymob order ID
            $table->morphs('payable'); // booking_id, etc.
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_cents', 12, 0); // Amount in cents for Paymob
            $table->string('currency')->default('SAR');
            $table->string('status')->default('pending'); // pending, success, failed, refunded
            $table->string('payment_method')->nullable(); // card, wallet, etc.
            $table->string('card_type')->nullable(); // visa, mastercard, mada
            $table->string('card_last_four')->nullable();
            $table->json('gateway_response')->nullable(); // Full response from gateway
            $table->text('error_message')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_settings');
        
        if (Schema::hasColumn('bookings', 'payment_reference')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn(['payment_reference', 'payment_method', 'payment_data']);
            });
        }
    }
};
