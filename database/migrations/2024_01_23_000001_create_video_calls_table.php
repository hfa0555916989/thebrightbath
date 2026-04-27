<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('room_token', 64)->unique();
            $table->enum('status', ['waiting', 'active', 'ended'])->default('waiting');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            
            $table->index(['booking_id', 'status']);
        });
        
        // جدول لتخزين إشارات WebRTC
        Schema::create('video_call_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_call_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['offer', 'answer', 'ice_candidate']);
            $table->longText('data'); // JSON data for SDP or ICE candidate
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index(['video_call_id', 'to_user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_call_signals');
        Schema::dropIfExists('video_calls');
    }
};

