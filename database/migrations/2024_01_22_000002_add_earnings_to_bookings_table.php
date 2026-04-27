<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'consultant_earnings')) {
                $table->decimal('consultant_earnings', 10, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('bookings', 'admin_earnings')) {
                $table->decimal('admin_earnings', 10, 2)->default(0)->after('consultant_earnings');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['consultant_earnings', 'admin_earnings']);
        });
    }
};



