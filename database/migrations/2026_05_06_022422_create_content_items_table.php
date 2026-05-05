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
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->string('type');         // stat, feature, service, step, testimonial, value, goal, team, faq, process_step
            $table->string('page')->default('home'); // home, about, services, assessments, consultations, contact, global
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();     // Font Awesome class e.g. "fas fa-medal"
            $table->string('color')->nullable();    // hex color e.g. "#F8C524"
            $table->string('link')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();       // extra: rating, role, city, badge, duration, questions_count...
            $table->timestamps();

            $table->index(['type', 'page', 'is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};
