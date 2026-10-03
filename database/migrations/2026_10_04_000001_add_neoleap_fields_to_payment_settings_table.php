<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Al Rajhi / Neoleap payment gateway credentials (entered from the admin panel).
 * Password and resource key are stored encrypted (Laravel "encrypted" cast).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->string('tranportal_id')->nullable()->after('gateway');
            $table->text('tranportal_password')->nullable()->after('tranportal_id');
            $table->text('resource_key')->nullable()->after('tranportal_password');
            $table->string('endpoint_url', 500)->nullable()->after('resource_key');
        });
    }

    public function down(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn(['tranportal_id', 'tranportal_password', 'resource_key', 'endpoint_url']);
        });
    }
};
