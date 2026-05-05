<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MountainManagementSeeder extends Seeder
{
    // Define name arrays as class properties
    private $firstNames = [
        'John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'William', 'Jessica',
        'James', 'Ashley', 'Christopher', 'Amanda', 'Daniel', 'Stephanie', 'Matthew', 'Jennifer', 'Anthony', 'Elizabeth',
        'Mark', 'Deborah', 'Donald', 'Rachel', 'Steven', 'Carolyn', 'Paul', 'Janet', 'Andrew', 'Catherine',
        'Kenneth', 'Maria', 'Joshua', 'Heather', 'Kevin', 'Diane', 'Brian', 'Ruth', 'George', 'Julie',
        'Timothy', 'Joyce', 'Ronald', 'Virginia', 'Jason', 'Victoria', 'Edward', 'Kelly', 'Jeffrey', 'Christina'
    ];

    private $lastNames = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
        'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
        'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson',
        'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores',
        'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell', 'Carter', 'Roberts'
    ];

    public function run()
    {
        $this->seedMountains();
        $this->seedUsers();
        $this->seedMountainBookings();
        $this->seedMountainEquipments();
        $this->seedMountainEquipmentRentals();
        $this->seedMountainSosSignals();
        $this->seedMountainFeedbacks();
        $this->seedMountainComplaints();
        $this->seedMountainHealthLogs();
        $this->seedMountainLocationLogs();
    }

    private function seedMountains()
    {
        $mountains = [
            'Mount Everest', 'Mount Kilimanjaro', 'Mount Fuji', 'Mount McKinley', 'Mount Elbrus',
            'Mount Whitney', 'Mount Washington', 'Mount Rainier', 'Mount Shasta', 'Mount Hood',
            'Mount Baker', 'Mount Adams', 'Mount Jefferson', 'Mount Olympus', 'Mount Logan',
            'Mount Robson', 'Mount Assiniboine', 'Mount Temple', 'Mount Athabasca', 'Mount Columbia',
            'Mount Forbes', 'Mount Bryce', 'Mount Clemenceau', 'Mount Goodsir', 'Mount Hungabee',
            'Mount Lefroy', 'Mount Victoria', 'Mount Huber', 'Mount Stephen', 'Mount Field',
            'Mount Burgess', 'Mount Wapta', 'Mount Hector', 'Mount Balfour', 'Mount Daly',
            'Mount Gordon', 'Mount Murchison', 'Mount Chephren', 'Mount Wilson', 'Mount Saskatchewan',
            'Mount Brazeau', 'Mount Fryatt', 'Mount Edith Cavell', 'Mount Kerkeslin', 'Mount Hardisty',
            'Mount Maligne', 'Mount Unwin', 'Mount Charlton', 'Mount Brussels', 'Mount Leah'
        ];

        $locations = [
            'Nepal/Tibet', 'Tanzania', 'Japan', 'Alaska', 'Russia',
            'California', 'New Hampshire', 'Washington', 'California', 'Oregon',
            'Washington', 'Washington', 'Oregon', 'Washington', 'Canada',
            'Canada', 'Canada', 'Canada', 'Canada', 'Canada',
            'Canada', 'Canada', 'Canada', 'Canada', 'Canada',
            'Canada', 'Canada', 'Canada', 'Canada', 'Canada',
            'Canada', 'Canada', 'Canada', 'Canada', 'Canada',
            'Canada', 'Canada', 'Canada', 'Canada', 'Canada',
            'Canada', 'Canada', 'Canada', 'Canada', 'Canada',
            'Canada', 'Canada', 'Canada', 'Canada', 'Canada'
        ];

        $statuses = ['active', 'inactive', 'pending'];

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountains')->insert([
                'name' => $mountains[$i],
                'location' => $locations[$i],
                'description' => "Beautiful mountain located in {$locations[$i]} with challenging trails and stunning views.",
                'banner_image_url' => "https://example.com/images/mountain_{$i}.jpg",
                'content' => "Detailed information about {$mountains[$i]} including hiking routes, difficulty levels, and safety guidelines.",
                'gallery' => json_encode([
                    "https://example.com/gallery/mountain_{$i}_1.jpg",
                    "https://example.com/gallery/mountain_{$i}_2.jpg",
                    "https://example.com/gallery/mountain_{$i}_3.jpg"
                ]),
                'faq' => json_encode([
                    ['question' => 'What is the best time to climb?', 'answer' => 'Spring and summer months are ideal.'],
                    ['question' => 'Do I need a permit?', 'answer' => 'Yes, permits are required for all climbs.'],
                    ['question' => 'What equipment is needed?', 'answer' => 'Basic mountaineering gear and proper clothing.']
                ]),
                'meta' => json_encode([
                    'elevation' => rand(1000, 8848),
                    'difficulty' => ['easy', 'moderate', 'difficult', 'expert'][rand(0, 3)],
                    'estimated_duration' => rand(1, 14) . ' days'
                ]),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30))
            ]);
        }
    }

    private function seedUsers()
    {
        $userTypes = ['pendaki', 'admin', 'main_admin'];
        // Get mountain IDs safely
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();
        if (empty($mountainIds)) {
            // If no mountains exist, create some basic mountain records first
            for ($j = 1; $j <= 5; $j++) {
                DB::table('mountains')->insert([
                    'name' => "Mountain $j",
                    'location' => "Location $j",
                    'description' => "Description for Mountain $j",
                    'banner_image_url' => "https://example.com/mountain_$j.jpg",
                    'content' => "Content for Mountain $j",
                    'gallery' => json_encode([]),
                    'faq' => json_encode([]),
                    'meta' => json_encode([]),
                    'status' => 'active',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
            $mountainIds = DB::table('mountains')->pluck('id')->toArray();
        }

        for ($i = 0; $i < 50; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $userType = $userTypes[array_rand($userTypes)];

            DB::table('users')->insert([
                'name' => $firstName . ' ' . $lastName,
                'nik' => sprintf('%016d', rand(1000000000000000, 9999999999999999)),
                'email' => strtolower($firstName . '.' . $lastName . rand(1, 999) . '@example.com'),
                'phone' => '+62' . rand(8000000000, 8999999999),
                'emergency_contact' => '+62' . rand(8000000000, 8999999999),
                'password' => Hash::make('password123'),
                'user_type' => $userType,
                'mountain_id' => ($userType !== 'pendaki' && !empty($mountainIds)) ? $mountainIds[array_rand($mountainIds)] : null,
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30))
            ]);
        }
    }

    private function seedMountainBookings()
    {
        $userIds = DB::table('users')->where('user_type', 'pendaki')->pluck('id')->toArray();
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();

        // Skip if no users or mountains exist
        if (empty($userIds) || empty($mountainIds)) {
            return;
        }

        $statuses = ['active', 'cancelled', 'completed'];

        for ($i = 0; $i < 50; $i++) {
            $hikeDate = Carbon::now()->addDays(rand(1, 180));
            $returnDate = $hikeDate->copy()->addDays(rand(1, 14));
            $teamSize = rand(1, 8);

            $members = [];
            for ($j = 0; $j < $teamSize; $j++) {
                $members[] = [
                    'name' => $this->firstNames[array_rand($this->firstNames)] . ' ' . $this->lastNames[array_rand($this->lastNames)],
                    'nik' => sprintf('%016d', rand(1000000000000000, 9999999999999999)),
                    'phone' => '+62' . rand(8000000000, 8999999999)
                ];
            }

            $status = $statuses[array_rand($statuses)];

            DB::table('mountain_bookings')->insert([
                'user_id' => $userIds[array_rand($userIds)],
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'hike_date' => $hikeDate,
                'return_date' => $returnDate,
                'team_size' => $teamSize,
                'members' => json_encode($members),
                'status' => $status,
                'qr_code' => 'QR' . strtoupper(bin2hex(random_bytes(8))),
                'checkin_time' => ($status !== 'active') ? Carbon::now()->subDays(rand(1, 30)) : null,
                'checkout_time' => ($status === 'completed') ? Carbon::now()->subDays(rand(1, 15)) : null,
                'total_duration_minutes' => ($status === 'completed') ? rand(1440, 20160) : null,
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => Carbon::now()->subDays(rand(1, 7))
            ]);
        }
    }

    private function seedMountainEquipments()
    {
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();

        // Skip if no mountains exist
        if (empty($mountainIds)) {
            return;
        }

        $equipments = [
            'Hiking Boots', 'Backpack', 'Tent', 'Sleeping Bag', 'Hiking Poles',
            'Helmet', 'Rope', 'Carabiner', 'Headlamp', 'GPS Device',
            'First Aid Kit', 'Water Bottle', 'Compass', 'Map', 'Whistle',
            'Multi-tool', 'Flashlight', 'Rain Jacket', 'Gloves', 'Hat',
            'Sunglasses', 'Sunscreen', 'Insect Repellent', 'Emergency Blanket', 'Fire Starter',
            'Camping Stove', 'Cookware', 'Food Container', 'Water Filter', 'Binoculars',
            'Camera', 'Portable Charger', 'Solar Panel', 'Weather Radio', 'Altimeter',
            'Ice Axe', 'Crampons', 'Climbing Harness', 'Belay Device', 'Pulley',
            'Snow Shovel', 'Avalanche Probe', 'Beacon', 'Thermos', 'Knife',
            'Paracord', 'Duct Tape', 'Matches', 'Candles', 'Emergency Food'
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountain_equipments')->insert([
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'name' => $equipments[$i],
                'description' => "High-quality {$equipments[$i]} suitable for mountain climbing and hiking activities.",
                'quantity' => rand(5, 50),
                'image_url' => "https://example.com/equipment/" . strtolower(str_replace(' ', '_', $equipments[$i])) . ".jpg",
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30))
            ]);
        }
    }

    private function seedMountainEquipmentRentals()
    {
        $bookingIds = DB::table('mountain_bookings')->pluck('id')->toArray();
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();
        $equipmentIds = DB::table('mountain_equipments')->pluck('id')->toArray();

        // Skip if no data exists
        if (empty($bookingIds) || empty($mountainIds) || empty($equipmentIds)) {
            return;
        }

        $statuses = ['borrowed', 'returned'];

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountain_equipment_rentals')->insert([
                'booking_id' => $bookingIds[array_rand($bookingIds)],
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'equipment_id' => $equipmentIds[array_rand($equipmentIds)],
                'quantity' => rand(1, 5),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => Carbon::now()->subDays(rand(1, 7))
            ]);
        }
    }

    private function seedMountainSosSignals()
    {
        $bookingIds = DB::table('mountain_bookings')->pluck('id')->toArray();
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();

        // Skip if no data exists
        if (empty($bookingIds) || empty($mountainIds)) {
            return;
        }

        $emergencyMessages = [
            'Help! We are lost and need assistance.',
            'Medical emergency - team member injured.',
            'Bad weather conditions, seeking shelter.',
            'Equipment failure, need rescue.',
            'Lost trail, require navigation help.',
            'Food supplies running low.',
            'Water shortage emergency.',
            'Team member has altitude sickness.',
            'Avalanche risk, need immediate help.',
            'Communication device broken, send help.'
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountain_sos_signals')->insert([
                'booking_id' => $bookingIds[array_rand($bookingIds)],
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'timestamp' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 23)),
                'latitude' => rand(-90000, 90000) / 1000,
                'longitude' => rand(-180000, 180000) / 1000,
                'message' => $emergencyMessages[array_rand($emergencyMessages)]
            ]);
        }
    }

    private function seedMountainFeedbacks()
    {
        $bookingIds = DB::table('mountain_bookings')->where('status', 'completed')->pluck('id')->toArray();
        if (empty($bookingIds)) {
            $bookingIds = DB::table('mountain_bookings')->pluck('id')->toArray();
        }
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();

        $comments = [
            'Amazing experience! The trail was well-marked and the views were breathtaking.',
            'Great mountain for beginners. Staff was very helpful and professional.',
            'Challenging but rewarding climb. Equipment rental service was excellent.',
            'Beautiful scenery throughout the hike. Would definitely recommend to others.',
            'Well-organized booking system and safety measures were top-notch.',
            'The mountain offered diverse terrain and wildlife. Loved every moment.',
            'Perfect for a weekend getaway. Clean facilities and friendly guides.',
            'Spectacular sunrise views from the summit. Worth every step of the climb.',
            'Good trail maintenance and clear signage. Safety equipment was in great condition.',
            'Memorable adventure with family. The mountain has something for everyone.'
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountain_feedbacks')->insert([
                'booking_id' => $bookingIds[array_rand($bookingIds)],
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'rating' => rand(1, 5),
                'comment' => $comments[array_rand($comments)],
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'updated_at' => Carbon::now()->subDays(rand(1, 90))
            ]);
        }
    }

    private function seedMountainComplaints()
    {
        $bookingIds = DB::table('mountain_bookings')->pluck('id')->toArray();
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();

        $complaints = [
            'Trail was not properly maintained, encountered several fallen trees.',
            'Equipment provided was old and not in good condition.',
            'Poor weather information provided before the hike.',
            'Restroom facilities were not clean and well-maintained.',
            'Guide was late and not well-prepared for the tour.',
            'Overpriced equipment rental compared to other locations.',
            'Lack of proper safety briefing before starting the hike.',
            'Trail markers were confusing and led us off-path.',
            'Emergency contact system was not working properly.',
            'Parking area was inadequate for the number of visitors.'
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountain_complaints')->insert([
                'booking_id' => $bookingIds[array_rand($bookingIds)],
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'message' => $complaints[array_rand($complaints)],
                'image_url' => rand(0, 1) ? "https://example.com/complaints/complaint_{$i}.jpg" : null,
                'created_at' => Carbon::now()->subDays(rand(1, 60))
            ]);
        }
    }

    private function seedMountainHealthLogs()
    {
        $bookingIds = DB::table('mountain_bookings')->pluck('id')->toArray();
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountain_health_logs')->insert([
                'booking_id' => $bookingIds[array_rand($bookingIds)],
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'heart_rate' => rand(60, 180),
                'body_temperature' => rand(360, 390) / 10,
                'timestamp' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 23))
            ]);
        }
    }

    private function seedMountainLocationLogs()
    {
        $bookingIds = DB::table('mountain_bookings')->pluck('id')->toArray();
        $mountainIds = DB::table('mountains')->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            DB::table('mountain_location_logs')->insert([
                'booking_id' => $bookingIds[array_rand($bookingIds)],
                'mountain_id' => $mountainIds[array_rand($mountainIds)],
                'latitude' => rand(-90000, 90000) / 1000,
                'longitude' => rand(-180000, 180000) / 1000,
                'timestamp' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 23))
            ]);
        }
    }
}
