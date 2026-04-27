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
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('specialization'); // التخصص
            $table->string('specialization_ar'); // التخصص بالعربي
            $table->text('bio')->nullable(); // نبذة عن المستشار
            $table->text('bio_ar')->nullable(); // نبذة بالعربي
            $table->string('photo')->nullable(); // صورة المستشار
            $table->decimal('price_per_30_min', 8, 2)->default(115.00); // السعر لكل 30 دقيقة
            $table->decimal('price_per_60_min', 8, 2)->default(200.00); // السعر لكل ساعة
            $table->integer('experience_years')->default(0); // سنوات الخبرة
            $table->json('certifications')->nullable(); // الشهادات
            $table->string('meeting_link')->nullable(); // رابط الاجتماع (Zoom/Google Meet)
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // مميز
            $table->integer('total_sessions')->default(0); // عدد الجلسات
            $table->decimal('rating', 2, 1)->default(5.0); // التقييم
            $table->integer('reviews_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultants');
    }
};





