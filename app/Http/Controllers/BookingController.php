<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(){
        $user = Auth::user();

        return view('booking', compact('user'));
    }

    public function booking(){
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk untuk melakukan booking.');
        }

        request()->validate([
            'mountain_id' => ['required', 'integer'],
            'hike_date' => ['required', 'date'],
            'return_date' => ['required', 'date', 'after_or_equal:hike_date'],
            'team_size' => ['required', 'integer', 'min:1'],
            'members' => ['nullable', 'string'], // Changed from array to string (JSON)
        ]);

        $userId = Auth::id();
        $mountainId = (int) request('mountain_id', 1);
        $hike_date = Carbon::parse(request('hike_date'));
        $return_date = Carbon::parse(request('return_date'));
        $teamSize = (int) request('team_size');

        // Decode the JSON string members
        $members = json_decode(request('members', '[]'), true);

        $totalDurationMinutes = $return_date->diffInMinutes($hike_date);

        DB::table('mountain_bookings')->insert([
            'user_id' => $userId,
            'mountain_id' => $mountainId,
            'hike_date' => $hike_date->toDateString(),
            'return_date' => $return_date->toDateString(),
            'team_size' => $teamSize,
            'members' => json_encode($members),
            'status' => 'active',
            'qr_code' => '',
            'checkin_time' => null,
            'checkout_time' => null,
            'total_duration_minutes' => $totalDurationMinutes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('booking-history')->with('success', 'Booking berhasil dibuat.');
    }

    public function downloadTicket($id)
    {
        $booking = DB::table('mountain_bookings as mb')
            ->join('mountains as m', 'mb.mountain_id', '=', 'm.id')
            ->select(
                'mb.*',
                'm.name as mountain_name'
            )
            ->where('mb.id', $id)
            ->where('mb.user_id', Auth::id())
            ->first();

        if (!$booking) {
            abort(404);
        }

        return view('booking-ticket', compact('booking'));
    }

    public function dashboardIndex()
    {
        return view('dashboard.pages.bookings.index');
    }

    public function getData(Request $request)
    {
        $mountainId = Auth::user()->mountain_id;

        $search = $request->get('search', '');
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);

        $query = DB::table('mountain_bookings as mb')
            ->join('users as u', 'mb.user_id', '=', 'u.id')
            ->join('mountains as m', 'mb.mountain_id', '=', 'm.id')
            ->select(
                'mb.id',
                'u.name as user_name',
                'u.email',
                'm.name as mountain_name',
                'mb.hike_date',
                'mb.return_date',
                'mb.team_size',
                'mb.status',
                'mb.checkin_time',
                'mb.checkout_time'
            )
            ->where('mb.mountain_id', $mountainId);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mb.id', 'LIKE', "%{$search}%")
                    ->orWhere('u.name', 'LIKE', "%{$search}%")
                    ->orWhere('u.email', 'LIKE', "%{$search}%")
                    ->orWhere('m.name', 'LIKE', "%{$search}%");
            });
        }

        $totalRecords = $query->count();

        $bookings = $query
            ->orderBy('mb.created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $bookings->map(function ($b) {

            return [
                'id' => $b->id,
                'user_name' => $b->user_name,
                'email' => $b->email,
                'mountain_name' => $b->mountain_name,
                'team_size' => $b->team_size,
                'status' => $b->status,

                'hike_date' => $b->hike_date
                    ? date('d M Y', strtotime($b->hike_date))
                    : '-',

                'return_date' => $b->return_date
                    ? date('d M Y', strtotime($b->return_date))
                    : '-',

                'checkin_time' => $b->checkin_time
                    ? date('d M Y H:i', strtotime($b->checkin_time))
                    : '-',

                'checkout_time' => $b->checkout_time
                    ? date('d M Y H:i', strtotime($b->checkout_time))
                    : '-',
            ];
        });

        return response()->json([
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }


    public function checkin($id)
    {
        DB::table('mountain_bookings')
            ->where('id', $id)
            ->update([
                'checkin_time' => now(),
                'status' => 'checked_in'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaki berhasil check-in'
        ]);
    }


    public function checkout($id)
    {
        DB::table('mountain_bookings')
            ->where('id', $id)
            ->update([
                'checkout_time' => now(),
                'status' => 'completed'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaki berhasil check-out'
        ]);
    }
}
