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
        Schema::create('mountain_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('mountain_id')->nullable()->constrained('mountains')->cascadeOnDelete();
            $table->date('hike_date')->nullable();
            $table->date('return_date')->nullable();
            $table->integer('team_size')->nullable();
            $table->json('members')->nullable();
            $table->enum('status', ['active', 'checked_in', 'completed', 'cancelled'])->default('active');
            $table->string('qr_code')->nullable();
            $table->timestamp('checkin_time')->nullable();
            $table->timestamp('checkout_time')->nullable();
            $table->integer('total_duration_minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountain_bookings');
    }
};
