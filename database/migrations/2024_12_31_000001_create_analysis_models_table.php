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
        Schema::create('analysis_models', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم النموذج
            $table->string('slug')->unique(); // الرابط الفريد
            $table->text('description')->nullable(); // وصف النموذج
            $table->string('icon')->default('fa-file-alt'); // أيقونة النموذج
            $table->string('color')->default('#D4AF37'); // لون النموذج
            $table->string('original_file_name')->nullable(); // اسم الملف الأصلي
            $table->string('file_path')->nullable(); // مسار ملف Excel
            $table->json('structure')->nullable(); // بنية النموذج (headers, fields, etc.)
            $table->json('data')->nullable(); // البيانات المستخرجة من Excel
            $table->json('settings')->nullable(); // إعدادات إضافية
            $table->boolean('is_active')->default(true); // حالة النموذج
            $table->boolean('is_featured')->default(false); // نموذج مميز
            $table->integer('order')->default(0); // ترتيب العرض
            $table->integer('views_count')->default(0); // عدد المشاهدات
            $table->integer('downloads_count')->default(0); // عدد التحميلات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_models');
    }
};
