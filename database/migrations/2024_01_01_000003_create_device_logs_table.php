<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Device Logs Table
 * Records all device activity, errors, and control events for troubleshooting
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('log_type', ['info', 'warning', 'error', 'control']); // Log category
            $table->string('action');                      // What happened (e.g., "Device turned ON")
            $table->text('message');                       // Detailed description
            $table->string('ip_address')->nullable();      // Source IP of the action
            $table->json('metadata')->nullable();          // Extra data (e.g., temperature value)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_logs');
    }
};
