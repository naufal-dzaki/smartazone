<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Mountains
        DB::table('mountains')->insert([
            [
                'id' => 1, 'name' => 'Gunung Rinjani', 'subdomains' => 'rinjani', 'location' => 'Lombok, Nusa Tenggara Barat',
                'description' => 'Gunung berapi aktif dengan danau Segara Anak.', 'status' => 'active',
                'gallery' => json_encode(["rinjani1.jpg", "rinjani2.jpg"]),
                'faq' => json_encode([["question" => "Perlu izin?", "answer" => "Ya"]]),
                'meta' => json_encode(["altitude" => 3726, "province" => "NTB"]),
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'id' => 2, 'name' => 'Gunung Semeru', 'subdomains' => 'semeru', 'location' => 'Lumajang, Jawa Timur',
                'description' => 'Gunung tertinggi di Jawa dengan puncak Mahameru.', 'status' => 'active',
                'gallery' => json_encode(["semeru1.jpg", "semeru2.jpg"]),
                'faq' => json_encode([["question" => "Aktif?", "answer" => "Ya"]]),
                'meta' => json_encode(["altitude" => 3676, "province" => "Jawa Timur"]),
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ]
        ]);

        // 2. Seed Users
        // Catatan: Semua password diseragamkan menjadi 'password' agar mudah saat testing
        $defaultPassword = Hash::make('password');
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Andi Setiawan', 'email' => 'andi@example.com', 'user_type' => 'pendaki', 'mountain_id' => 1, 'password' => $defaultPassword],
            ['id' => 2, 'name' => 'Siti Rahma', 'email' => 'siti@example.com', 'user_type' => 'pendaki', 'mountain_id' => 2, 'password' => $defaultPassword],
            ['id' => 3, 'name' => 'Admin Rinjani', 'email' => 'adminrinjani@gmail.com', 'user_type' => 'admin', 'mountain_id' => 1, 'password' => $defaultPassword],
            ['id' => 4, 'name' => 'Super Admin', 'email' => 'superadmin@example.com', 'user_type' => 'superadmin', 'mountain_id' => null, 'password' => $defaultPassword],
            ['id' => 11, 'name' => 'Naufal', 'email' => 'naufal@gmail.com', 'user_type' => 'pendaki', 'mountain_id' => null, 'password' => $defaultPassword],
        ]);

        // 3. Seed Mountain Devices
        DB::table('mountain_devices')->insert([
            ['id' => 1, 'mountain_id' => 1, 'battery_level' => 95],
            ['id' => 2, 'mountain_id' => 2, 'battery_level' => 87],
            ['id' => 4, 'mountain_id' => 1, 'battery_level' => 85],
            ['id' => 5, 'mountain_id' => 1, 'battery_level' => 80],
        ]);

        // 4. Seed Mountain Bookings (Ambil sampel representatif)
        DB::table('mountain_bookings')->insert([
            ['id' => 1, 'user_id' => 1, 'mountain_id' => 1, 'status' => 'completed', 'hike_date' => '2025-06-10', 'qr_code' => 'QR001'],
            ['id' => 2, 'user_id' => 2, 'mountain_id' => 2, 'status' => 'active', 'hike_date' => '2025-07-02', 'qr_code' => 'QR002'],
            ['id' => 12, 'user_id' => 11, 'mountain_id' => 1, 'status' => 'active', 'hike_date' => '2026-03-15', 'qr_code' => ''],
        ]);

        // 5. Seed Mountain Hiker Status (Data tidak ada duplikat, aman dimasukkan semua)
        DB::table('mountain_hiker_status')->insert([
            ['id' => 1, 'booking_id' => 1, 'mountain_id' => 1, 'device_id' => 1, 'hiker_name' => 'Andi Setiawan', 'status' => 'inactive'],
            ['id' => 2, 'booking_id' => 2, 'mountain_id' => 2, 'device_id' => 2, 'hiker_name' => 'Siti Rahma', 'status' => 'active'],
            ['id' => 4, 'booking_id' => 12, 'mountain_id' => 1, 'device_id' => 5, 'hiker_name' => 'Naufal', 'status' => 'active'],
        ]);

        // ==========================================
        // 6. Seed Mountain Hiker Logs (CLEANED UP - BEBAS DUPLIKAT SPAM)
        // ✅ TYPO FIXED: lattitude -> latitude
        // ==========================================
        DB::table('mountain_hiker_logs')->insert([
            ['id' => 1, 'device_id' => 1, 'heart_rate' => 98, 'spo2' => 97, 'latitude' => -8.409518, 'longitude' => 116.456421, 'timestamp' => '2025-11-10 14:05:01'],
            ['id' => 2, 'device_id' => 1, 'heart_rate' => 102, 'spo2' => 95, 'latitude' => -8.410522, 'longitude' => 116.457601, 'timestamp' => '2025-11-11 14:05:01'],
            ['id' => 3, 'device_id' => 2, 'heart_rate' => 88, 'spo2' => 98, 'latitude' => -8.107621, 'longitude' => 112.922121, 'timestamp' => '2025-11-12 13:05:01'],
            // Device 4 ping spam dipangkas jadi 1
            ['id' => 4, 'device_id' => 4, 'heart_rate' => 91, 'spo2' => 95, 'latitude' => -8.411000, 'longitude' => 116.458000, 'timestamp' => '2026-03-12 19:06:04'],
            // Device 5 ping spam dipangkas jadi 2 titik beda
            ['id' => 8, 'device_id' => 5, 'heart_rate' => 85, 'spo2' => 98, 'latitude' => -8.4112, 'longitude' => 116.458, 'timestamp' => '2026-03-16 20:23:13'],
            ['id' => 10, 'device_id' => 5, 'heart_rate' => 90, 'spo2' => 97, 'latitude' => -7.250445, 'longitude' => 112.768845, 'timestamp' => '2026-04-19 20:56:04'],
        ]);

        // ==========================================
        // 7. Seed Mountain SOS Signals (CLEANED UP - BEBAS DUPLIKAT SPAM)
        // ✅ TYPO FIXED: lattitude -> latitude
        // ==========================================
        DB::table('mountain_sos_signals')->insert([
            // Sinyal SOS asli dari Device 1 dan 2
            ['id' => 1, 'device_id' => 1, 'latitude' => -8.408, 'longitude' => 116.46, 'timestamp' => '2025-11-07 14:05:01'],
            ['id' => 2, 'device_id' => 2, 'latitude' => -8.11, 'longitude' => 112.92, 'timestamp' => '2025-11-11 14:05:01'],

            // Sinyal SOS spam dari Device 5 (dipangkas dari 20+ baris menjadi 2 lokasi unik saja)
            ['id' => 3, 'device_id' => 5, 'latitude' => -8.4125, 'longitude' => 116.4595, 'timestamp' => '2026-03-16 20:42:57'],
            ['id' => 12, 'device_id' => 5, 'latitude' => -7.3337212, 'longitude' => 112.7883247, 'timestamp' => '2026-04-19 06:06:25'],
        ]);
    }
}
