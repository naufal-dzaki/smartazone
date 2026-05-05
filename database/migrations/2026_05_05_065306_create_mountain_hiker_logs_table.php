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
        Schema::create('mountain_hiker_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('mountain_devices')->cascadeOnDelete();
            $table->integer('heart_rate')->nullable();
            $table->integer('spo2')->nullable();
            $table->integer('stress_level')->nullable();
            $table->double('latitude')->nullable(); // ✅ TYPO FIXED
            $table->double('longitude')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountain_hiker_logs');
    }
};
