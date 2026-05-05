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
        Schema::create('mountain_equipment_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('mountain_bookings')->cascadeOnDelete();
            $table->foreignId('mountain_id')->nullable()->constrained('mountains')->cascadeOnDelete();
            $table->foreignId('equipment_id')->nullable()->constrained('mountain_equipments')->cascadeOnDelete();
            $table->integer('quantity')->nullable();
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountain_equipment_rentals');
    }
};
