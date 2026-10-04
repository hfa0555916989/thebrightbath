<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sessions now run on Daily.co: one private room per booking, created on first join.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_calls', function (Blueprint $table) {
            $table->string('daily_room_name')->nullable()->after('room_token');
            $table->string('daily_room_url', 500)->nullable()->after('daily_room_name');
        });
    }

    public function down(): void
    {
        Schema::table('video_calls', function (Blueprint $table) {
            $table->dropColumn(['daily_room_name', 'daily_room_url']);
        });
    }
};
