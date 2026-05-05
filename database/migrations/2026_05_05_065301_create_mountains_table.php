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
        Schema::create('mountains', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('subdomains')->unique()->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('banner_image_url')->nullable();
            $table->text('content')->nullable();
            $table->json('gallery')->nullable();
            $table->json('faq')->nullable();
            $table->json('meta')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountains');
    }
};
