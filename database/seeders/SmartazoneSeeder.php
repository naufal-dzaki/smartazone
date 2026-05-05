<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SmartazoneSeeder extends Seeder
{
    public function run()
    {
        // 1. Gunakan updateOrInsert untuk Mountain
        DB::table('mountains')->updateOrInsert(
            ['subdomains' => 'rinjani'], // Cek berdasarkan ini
            [
                'name' => 'Gunung Rinjani',
                'location' => 'Lombok, Nusa Tenggara Barat',
                'status' => 'active',
                'updated_at' => now(),
            ]
        );

        $mountain = DB::table('mountains')->where('subdomains', 'rinjani')->first();

        // 2. Seed User (Gunakan updateOrInsert berdasarkan email)
        DB::table('users')->updateOrInsert(
            ['email' => 'pendaki_test@example.com'],
            [
                'name' => 'Budi Pendaki',
                'password' => Hash::make('password'),
                'user_type' => 'pendaki',
                'mountain_id' => $mountain->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $user = DB::table('users')->where('email', 'pendaki_test@example.com')->first();

        // 3. Seed Device
        $deviceId = DB::table('mountain_devices')->insertGetId([
            'battery_level' => 85,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Seed Booking (Status 'active' agar muncul di Dashboard)
        $bookingId = DB::table('mountain_bookings')->insertGetId([
            'user_id' => $user->id,
            'mountain_id' => $mountain->id,
            'hike_date' => Carbon::now()->toDateString(),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Seed Hiker Status (Menghubungkan Booking ke Device)
        DB::table('mountain_hiker_status')->insert([
            'booking_id' => $bookingId,
            'mountain_id' => $mountain->id,
            'device_id' => $deviceId,
            'hiker_name' => $user->name,
            'status' => 'active',
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Seed Hiker Logs (Data sensor untuk grafik/tabel)
        for ($i = 3; $i >= 0; $i--) {
            DB::table('mountain_hiker_logs')->insert([
                'device_id' => $deviceId,
                'heart_rate' => rand(70, 100),
                'spo2' => rand(95, 99),
                'stress_level' => rand(10, 30),
                'timestamp' => Carbon::now()->subMinutes($i * 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
