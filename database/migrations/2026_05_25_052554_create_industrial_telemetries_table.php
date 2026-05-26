<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('industrial_telemetries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industrial_device_id')->constrained()->onDelete('cascade');
            $table->string('metric_name');
            $table->float('value', 8, 2);
            $table->string('unit')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('industrial_telemetries');
    }
};