<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check if assessment_id column exists and make it nullable
        if (Schema::hasColumn('assessment_attempts', 'assessment_id')) {
            // Drop the foreign key constraint first
            Schema::table('assessment_attempts', function (Blueprint $table) {
                // Try to drop foreign key if exists
                try {
                    $table->dropForeign(['assessment_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
            });
            
            // Modify the column to be nullable with default null
            DB::statement('ALTER TABLE `assessment_attempts` MODIFY `assessment_id` BIGINT UNSIGNED NULL DEFAULT NULL');
        }
        
        // Add 'completed' to status enum if it doesn't exist
        DB::statement("ALTER TABLE `assessment_attempts` MODIFY COLUMN `status` ENUM('new', 'viewed', 'reviewed', 'in_progress', 'completed') DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the changes
        if (Schema::hasColumn('assessment_attempts', 'assessment_id')) {
            DB::statement('ALTER TABLE `assessment_attempts` MODIFY `assessment_id` BIGINT UNSIGNED NOT NULL');
        }
        
        DB::statement("ALTER TABLE `assessment_attempts` MODIFY COLUMN `status` ENUM('new', 'viewed', 'reviewed', 'in_progress') DEFAULT 'new'");
    }
};

