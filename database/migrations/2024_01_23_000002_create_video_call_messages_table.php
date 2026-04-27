<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_call_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_call_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['text', 'file'])->default('text');
            $table->text('content')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();
            
            $table->index(['video_call_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_call_messages');
    }
};


