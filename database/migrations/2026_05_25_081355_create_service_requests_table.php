<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('service_name')->default('Tech Team Booking');
            $table->text('address');
            $table->decimal('amount', 8, 2)->default(2.00);
            $table->string('razorpay_payment_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
