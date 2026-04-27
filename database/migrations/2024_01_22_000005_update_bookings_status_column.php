<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update the status enum to include new statuses
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending_approval', 'approved', 'pending', 'confirmed', 'completed', 'cancelled', 'rejected', 'no_show') NOT NULL DEFAULT 'pending_approval'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no_show') NOT NULL DEFAULT 'pending'");
    }
};
