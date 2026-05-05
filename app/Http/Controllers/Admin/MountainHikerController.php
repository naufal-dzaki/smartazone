<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MountainHikerController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.mountain-hikers.index');
    }

    public function getList(Request $request)
    {
        $mountainId = Auth::user()->mountain_id;

        $bookings = DB::table('mountain_bookings as mb')
            ->join('users as u', 'u.id', '=', 'mb.user_id')
            ->leftJoin('mountain_hiker_status as hs', 'mb.id', '=', 'hs.booking_id')
            ->leftJoin('mountain_devices as d', 'hs.device_id', '=', 'd.id')
            ->select(
                'mb.id as booking_id',
                'u.name as user_name',
                'u.phone',
                'mb.hike_date',
                'mb.return_date',
                'mb.team_size',
                'hs.device_id'
            )
            ->where('mb.mountain_id', $mountainId)
            ->where('mb.status', 'active')
            ->groupBy(
                'mb.id',
                'u.name',
                'u.phone',
                'mb.hike_date',
                'mb.return_date',
                'mb.team_size',
                'hs.device_id'
            )
            ->get();

        $data = $bookings->map(function ($b) {
            if (!$b->device_id) {
                $b->latitude = null;
                $b->longitude = null;
                $b->timestamp = null;
                return $b;
            }

            $lastLog = DB::table('mountain_hiker_logs as hl')
                ->where('hl.device_id', $b->device_id)
                ->whereNotNull('hl.latitude')
                ->whereNotNull('hl.longitude')
                ->orderByDesc('hl.timestamp')
                ->first(['hl.latitude', 'hl.longitude', 'hl.timestamp']);

            $b->latitude = $lastLog->latitude ?? null;
            $b->longitude = $lastLog->longitude ?? null;
            $b->timestamp = $lastLog->timestamp ?? null;
            return $b;
        });

        return response()->json(['data' => $data]);
    }

public function getLogs(Request $request)
{
    $bookingId = $request->get('id');

    $device = DB::table('mountain_hiker_status')
        ->where('booking_id', $bookingId)
        ->value('device_id');

    if (!$device) {
        return response()->json(['logs' => []]);
    }

    $fields = ['latitude', 'longitude', 'heart_rate', 'spo2', 'stress_level'];
    $log = [];

    foreach ($fields as $field) {
        $log[$field] = DB::table('mountain_hiker_logs')
            ->where('device_id', $device)
            ->whereNotNull($field)
            ->orderByDesc('timestamp')
            ->value($field);
    }

    $log['timestamp'] = DB::table('mountain_hiker_logs')
        ->where('device_id', $device)
        ->orderByDesc('timestamp')
        ->value('timestamp');

    return response()->json(['logs' => $log]);
}


}
