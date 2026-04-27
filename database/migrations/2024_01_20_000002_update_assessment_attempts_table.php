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
        Schema::table('assessment_attempts', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('assessment_attempts', 'assessment_slug')) {
                $table->string('assessment_slug')->nullable()->after('guest_phone');
            }
            if (!Schema::hasColumn('assessment_attempts', 'assessment_name')) {
                $table->string('assessment_name')->nullable()->after('assessment_slug');
            }
            if (!Schema::hasColumn('assessment_attempts', 'type_code')) {
                $table->string('type_code', 50)->nullable()->after('scores_json');
            }
            if (!Schema::hasColumn('assessment_attempts', 'summary')) {
                $table->text('summary')->nullable()->after('type_code');
            }
            if (!Schema::hasColumn('assessment_attempts', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('counselor_notes');
            }
            if (!Schema::hasColumn('assessment_attempts', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            $table->dropColumn(['assessment_slug', 'assessment_name', 'type_code', 'summary', 'ip_address', 'user_agent']);
        });
    }
};


