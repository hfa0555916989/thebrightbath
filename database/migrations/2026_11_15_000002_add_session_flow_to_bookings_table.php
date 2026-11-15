<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Booking flow without payment and session alerts:
 * - payment_status "waived": confirmed while payments are switched off (nothing to pay).
 * - confirmed_at: when the booking was confirmed without payment (paid ones use paid_at).
 * - room_open_sent_at: the "session room is open" email went out (once per booking).
 * - client_joined_at / consultant_joined_at: first time each party entered the live room,
 *   so the other party is told only once.
 * - consultants.notification_email: where the consultant wants booking emails (empty = account email).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'refunded', 'failed', 'waived') NOT NULL DEFAULT 'pending'");

        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->after('paid_at');
            $table->timestamp('room_open_sent_at')->nullable()->after('reminder_1h_sent_at');
            $table->timestamp('client_joined_at')->nullable()->after('room_open_sent_at');
            $table->timestamp('consultant_joined_at')->nullable()->after('client_joined_at');
        });

        Schema::table('consultants', function (Blueprint $table) {
            $table->string('notification_email')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropColumn('notification_email');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['confirmed_at', 'room_open_sent_at', 'client_joined_at', 'consultant_joined_at']);
        });

        DB::table('bookings')->where('payment_status', 'waived')->update(['payment_status' => 'pending']);
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'refunded', 'failed') NOT NULL DEFAULT 'pending'");
    }
};
