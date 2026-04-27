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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique(); // رقم الحجز
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // العميل
            $table->foreignId('consultant_id')->constrained()->onDelete('cascade'); // المستشار
            $table->date('booking_date'); // تاريخ الحجز
            $table->time('start_time'); // وقت البداية
            $table->time('end_time'); // وقت النهاية
            $table->integer('duration_minutes')->default(30); // مدة الجلسة
            $table->decimal('price', 8, 2); // السعر
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->text('client_notes')->nullable(); // ملاحظات العميل
            $table->text('consultant_notes')->nullable(); // ملاحظات المستشار
            $table->string('meeting_link')->nullable(); // رابط الاجتماع
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['consultant_id', 'booking_date', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['booking_date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};





