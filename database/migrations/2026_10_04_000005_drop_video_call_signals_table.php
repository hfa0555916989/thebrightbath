<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * WebRTC signalling table, unused since sessions moved to Daily.co.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('video_call_signals');
    }

    public function down(): void
    {
        Schema::create('video_call_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_call_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['offer', 'answer', 'ice_candidate']);
            $table->longText('data');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['video_call_id', 'to_user_id', 'is_read']);
        });
    }
};
