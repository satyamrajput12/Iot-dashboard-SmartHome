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
        Schema::table('devices', function (Blueprint $table) {
            $table->integer('brightness')->nullable()->default(100)->after('temperature');
            $table->string('mode')->nullable()->default('Cool')->after('brightness');
            $table->decimal('target_temperature', 5, 1)->nullable()->after('mode');
            $table->string('stream_url')->nullable()->after('target_temperature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['brightness', 'mode', 'target_temperature', 'stream_url']);
        });
    }
};
