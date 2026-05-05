<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SOSMonitoringController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.sos-monitoring.index');
    }

    public function getData(Request $request)
    {
        $search = $request->get('search', '');
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $mountainId = Auth::user()->mountain_id;

        $query = DB::table('mountain_sos_signals as mss')
            ->join('mountain_hiker_status as mh', 'mss.device_id', '=', 'mh.device_id')
            ->join('mountains as mt', 'mh.mountain_id', '=', 'mt.id')
            ->join('mountain_devices as md', 'mss.device_id', '=', 'md.id')
            ->select(
                'mss.id as sos_id',
                'mss.device_id',
                'mss.latitude',
                'mss.longitude',
                'mss.timestamp',
                'mh.hiker_name',
                'mh.hiker_phone',
                'mt.name as mountain_name',
                'md.battery_level'
            )
            ->where('mh.mountain_id', $mountainId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('mh.hiker_name', 'LIKE', "%{$search}%")
                  ->orWhere('mss.device_id', 'LIKE', "%{$search}%");
            });
        }

        $totalRecords = $query->count();

        $sosSignals = $query->orderBy('mss.timestamp', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $sosSignals->map(function ($s) {
            return [
                'sos_id' => $s->sos_id,
                'device_id' => $s->device_id,
                'hiker_name' => $s->hiker_name,
                'phone' => $s->hiker_phone,
                'mountain_name' => $s->mountain_name,
                'latitude' => $s->latitude,
                'longitude' => $s->longitude,
                'timestamp' => Carbon::parse($s->timestamp)->format('Y-m-d H:i:s'),
                'battery_level' => $s->battery_level,
                'priority' => $this->calculatePriority($s),
                'status' => 'pending'
            ];
        });

        return response()->json([
            'draw' => intval($request->get('draw', 1)),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function getSOSStats()
    {
        $mountainId = Auth::user()->mountain_id;

        $totalSOS = DB::table('mountain_sos_signals as mss')
            ->join('mountain_hiker_status as mh', 'mss.device_id', '=', 'mh.device_id')
            ->where('mh.mountain_id', $mountainId)
            ->count();

        $pendingSOS = $totalSOS;
        $resolvedSOS = 0;

        $recentSOS = DB::table('mountain_sos_signals as mss')
            ->join('mountain_hiker_status as mh', 'mss.device_id', '=', 'mh.device_id')
            ->join('mountains as mt', 'mh.mountain_id', '=', 'mt.id')
            ->select(
                'mss.id',
                'mh.hiker_name',
                'mt.name as mountain_name',
                'mss.timestamp'
            )
            ->where('mh.mountain_id', $mountainId)
            ->where('mss.timestamp', '>=', Carbon::now()->subHours(24))
            ->orderBy('mss.timestamp', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'total_sos' => $totalSOS,
            'pending_sos' => $pendingSOS,
            'resolved_sos' => $resolvedSOS,
            'recent_sos' => $recentSOS,
            'avg_response_time' => 0
        ]);
    }

    public function show($id)
    {
        $sos = DB::table('mountain_sos_signals as mss')
            ->join('mountain_hiker_status as mh', 'mss.device_id', '=', 'mh.device_id')
            ->join('mountains as mt', 'mh.mountain_id', '=', 'mt.id')
            ->join('mountain_devices as md', 'mss.device_id', '=', 'md.id')
            ->where('mss.id', $id)
            ->select(
                'mss.*',
                'mh.hiker_name',
                'mh.hiker_phone',
                'mt.name as mountain_name',
                'md.battery_level'
            )
            ->first();

        $latestLocation = DB::table('mountain_hiker_logs')
            ->where('device_id', $sos->device_id)
            ->orderBy('timestamp', 'desc')
            ->first();

        return response()->json([
            'sos_signal' => $sos,
            'latest_location' => $latestLocation
        ]);
    }

    private function calculatePriority($signal)
    {
        $hoursAgo = (time() - strtotime($signal->timestamp)) / 3600;
        if ($hoursAgo > 6) return 'critical';
        if ($hoursAgo > 2) return 'high';
        if ($hoursAgo > 1) return 'medium';
        return 'normal';
    }

    private function triggerEmergencyNotifications($sosId)
    {
        DB::table('emergency_notifications')->insert([
            'sos_signal_id' => $sosId,
            'notification_type' => 'emergency_alert',
            'status' => 'sent',
            'created_at' => Carbon::now()
        ]);
    }

    public function getEmergencyContacts($bookingId)
    {
        $contacts = DB::table('mountain_hiker_status as mh')
            ->join('mountain_bookings as mb', 'mh.booking_id', '=', 'mb.id')
            ->join('users as u', 'mb.user_id', '=', 'u.id')
            ->select('u.emergency_contact', 'u.phone')
            ->where('mh.booking_id', $bookingId)
            ->first();

        return response()->json($contacts);
    }

    public function createTmpsos()
    {
        $data = [
            'booking_id' => 'BK20240615001',
            'mountain_id' => 1,
            'latitude' => -7.123456,
            'longitude' => 112.123456,
            'message' => 'Butuh bantuan segera!',
            'timestamp' => now()
        ];

        event(new SosSignalCreated($data));
    }

      public function createSOSSignal(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:mountain_hiker_status,booking_id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'message' => 'nullable|string|max:500'
        ]);

        $booking = DB::table('mountain_hiker_status')
            ->where('booking_id', $request->booking_id)
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Data pendakian tidak ditemukan'], 404);
        }

        $sosId = DB::table('mountain_sos_signals')->insertGetId([
            'booking_id' => $request->booking_id,
            'mountain_id' => $booking->mountain_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'message' => $request->message,
            'timestamp' => Carbon::now()
        ]);

        $this->triggerEmergencyNotifications($sosId);

        return response()->json([
            'success' => 'Sinyal SOS berhasil dikirim',
            'sos_id' => $sosId
        ]);
    }
}
