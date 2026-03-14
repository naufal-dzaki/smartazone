<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE mountain_bookings
            MODIFY status ENUM(
                'active',
                'checked_in',
                'completed',
                'cancelled'
            ) DEFAULT 'active'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE mountain_bookings
            MODIFY status ENUM(
                'active',
                'completed',
                'cancelled'
            ) DEFAULT 'active'
        ");
    }
};
