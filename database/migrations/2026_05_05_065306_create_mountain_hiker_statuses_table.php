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
        Schema::create('mountain_hiker_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('mountain_bookings')->cascadeOnDelete();
            $table->foreignId('mountain_id')->nullable()->constrained('mountains')->cascadeOnDelete();
            $table->foreignId('device_id')->nullable()->constrained('mountain_devices')->nullOnDelete();
            $table->string('hiker_name')->nullable();
            $table->string('hiker_nik')->nullable();
            $table->string('hiker_phone')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountain_hiker_statuses');
    }
};
