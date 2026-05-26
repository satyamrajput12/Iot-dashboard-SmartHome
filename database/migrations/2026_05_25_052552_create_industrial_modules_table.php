<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('industrial_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // 'Industry 4.0' or 'Industrial IoT Solutions'
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('industrial_modules');
    }
};