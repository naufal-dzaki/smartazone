<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HikerLinkController extends Controller
{
    public function index()
    {
        $mountainId = Auth::user()->mountain_id;

        $bookings = DB::table('mountain_bookings as b')
            ->join('users as u', 'b.user_id', '=', 'u.id')
            ->where('b.mountain_id', $mountainId)
            ->select('b.id', 'b.members', 'b.mountain_id', 'b.qr_code', 'b.team_size',
                    'u.name as leader_name', 'u.phone as leader_phone', 'u.email as leader_email')
            ->orderByDesc('b.id')
            ->get()
            ->map(function ($b) {
                $members = json_decode($b->members, true) ?: [];
                $leader = [
                    'name'      => $b->leader_name ?? 'Unknown',
                    'email'     => $b->leader_email ?? null,
                    'phone'     => $b->leader_phone ?? null,
                    'nik'       => null,
                    'is_leader' => true,
                ];
                $b->members = array_merge([$leader], $members);
                return $b;
            });

        $devices = DB::table('mountain_devices')
            ->where('mountain_id', $mountainId)
            ->select('id', 'battery_level')
            ->orderBy('id')
            ->get();

        $linked = DB::table('mountain_hiker_status as hs')
            ->join('mountain_devices as d', 'hs.device_id', '=', 'd.id')
            ->join('mountain_bookings as b', 'hs.booking_id', '=', 'b.id')
            ->where('hs.mountain_id', $mountainId)
            ->select('hs.*', 'd.battery_level', 'b.mountain_id')
            ->orderByDesc('hs.id')
            ->get();

        return view('dashboard.pages.hiker_link.index', compact('bookings', 'devices', 'linked'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'device_id' => 'required|integer',
            'member_name' => 'required|string',
            'member_nik' => 'nullable|string',
            'member_phone' => 'nullable|string',
        ]);

        $mountainId = Auth::user()->mountain_id;

        $booking = DB::table('mountain_bookings as b')
            ->join('users as u', 'b.user_id', '=', 'u.id')
            ->where('b.mountain_id', $mountainId)
            ->where('b.id', $request->booking_id)
            ->select('b.id', 'b.members', 'b.qr_code', 'b.team_size',
                     'u.name as leader_name', 'u.phone as leader_phone', 'u.email as leader_email')
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking tidak valid untuk gunung ini');
        }


        $device = DB::table('mountain_devices')
            ->where('id', $request->device_id)
            ->where('mountain_id', $mountainId)
            ->first();

        if (!$device) {
            return back()->with('error', 'Device tidak valid untuk gunung ini');
        }

        $isUsed = DB::table('mountain_hiker_status')
            ->where('device_id', $request->device_id)
            ->where('status', 'active')
            ->exists();

        if ($isUsed) {
            return back()->with('error', 'Device sedang digunakan oleh pendaki lain!');
        }

        DB::table('mountain_hiker_status')->insert([
            'booking_id' => $booking->id,
            'mountain_id' => $mountainId,
            'device_id' => $request->device_id,
            'hiker_name' => $request->member_name,
            'hiker_nik' => $request->member_nik ?? '',
            'hiker_phone' => $request->member_phone ?? '',
            'status' => 'active',
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('hiker.link')->with('success', 'Hiker linked successfully.');
    }
}
