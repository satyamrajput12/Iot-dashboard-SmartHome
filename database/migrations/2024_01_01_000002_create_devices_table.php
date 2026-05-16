<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Devices Table
 * Stores all IoT smart home devices registered by users
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Owner
            $table->string('name');                        // Device name (e.g., "Living Room Light")
            $table->enum('type', ['thermostat', 'light', 'alarm', 'camera']); // Device category
            $table->string('location');                    // Room/area (e.g., "Living Room")
            $table->enum('status', ['on', 'off'])->default('off'); // Power state
            $table->decimal('temperature', 5, 2)->nullable(); // For thermostats (°C)
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();  // Admin note if rejected
            $table->string('device_id')->unique();         // Unique hardware ID (simulated)
            $table->text('description')->nullable();       // Optional notes
            $table->timestamp('last_seen')->nullable();    // Last activity timestamp
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
