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
        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->json('answers_json')->nullable();
            $table->json('scores_json')->nullable();
            $table->string('summary_level')->nullable();
            $table->string('report_path')->nullable();
            $table->enum('status', ['new', 'viewed', 'reviewed', 'in_progress'])->default('new');
            $table->foreignId('counselor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('counselor_notes')->nullable();
            $table->timestamps();

            $table->index(['assessment_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_attempts');
    }
};






