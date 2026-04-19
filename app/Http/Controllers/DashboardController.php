<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $mountainId = Auth::user()->mountain_id;

        $monthlyHikers = $this->getMonthlyHikers($mountainId);
        $totalRevenue = $this->getTotalRevenue($mountainId);
        $checkInOutTrends = $this->getCheckInOutTrends($mountainId);
        $favoriteRoutes = $this->getFavoriteRoutes($mountainId);
        $overallStats = $this->getOverallStats($mountainId);
        $recentBookings = $this->getRecentBookings($mountainId);

        return view('dashboard.pages.index', compact(
            'monthlyHikers',
            'totalRevenue',
            'checkInOutTrends',
            'favoriteRoutes',
            'overallStats',
            'recentBookings'
        ));
    }

    private function getMonthlyHikers($mountainId)
    {
        $currentYear = Carbon::now()->year;

        $monthlyData = DB::table('mountain_bookings')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(team_size) as total_hikers'),
                DB::raw('COUNT(id) as total_bookings')
            )
            ->whereYear('created_at', $currentYear)
            ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyData->firstWhere('month', $i);
            $result[] = [
                'month' => Carbon::create()->month($i)->format('M'),
                'hikers' => $monthData->total_hikers ?? 0,
                'bookings' => $monthData->total_bookings ?? 0,
            ];
        }

        return $result;
    }

    private function getTotalRevenue($mountainId)
    {
        $pricePerPerson = 50000;
        $currentMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        $query = DB::table('mountain_bookings')
            ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId));

        $currentMonthRevenue = (clone $query)
            ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $currentMonth)
            ->sum(DB::raw("team_size * {$pricePerPerson}"));

        $lastMonthRevenue = (clone $query)
            ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $lastMonth)
            ->sum(DB::raw("team_size * {$pricePerPerson}"));

        $totalRevenue = (clone $query)
            ->sum(DB::raw("team_size * {$pricePerPerson}"));

        $growthPercentage = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        return [
            'total' => $totalRevenue,
            'current_month' => $currentMonthRevenue,
            'last_month' => $lastMonthRevenue,
            'growth_percentage' => round($growthPercentage, 1),
        ];
    }

    private function getCheckInOutTrends($mountainId)
    {
        $trendData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $checkIns = DB::table('mountain_bookings')
                ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
                ->whereDate('checkin_time', $date)
                ->count();

            $checkOuts = DB::table('mountain_bookings')
                ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
                ->whereDate('checkout_time', $date)
                ->count();

            $trendData->push([
                'date' => $date->format('M d'),
                'check_ins' => $checkIns,
                'check_outs' => $checkOuts,
            ]);
        }

        $activeBookings = DB::table('mountain_bookings')
            ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
            ->where('status', 'active')
            ->whereNotNull('checkin_time')
            ->whereNull('checkout_time')
            ->count();

        return [
            'daily_trends' => $trendData,
            'active_bookings' => $activeBookings,
        ];
    }

    private function getFavoriteRoutes($mountainId)
    {
        $routes = DB::table('mountain_bookings as mb')
            ->join('mountains as m', 'mb.mountain_id', '=', 'm.id')
            ->leftJoin('mountain_feedbacks as f', 'mb.id', '=', 'f.booking_id')
            ->select(
                'm.name',
                'm.location',
                DB::raw('COUNT(mb.id) as total_bookings'),
                DB::raw('SUM(mb.team_size) as total_hikers'),
                DB::raw('ROUND(AVG(COALESCE(f.rating, 0)), 1) as avg_rating')
            )
            ->when($mountainId, fn($q) => $q->where('m.id', $mountainId))
            ->groupBy('m.id', 'm.name', 'm.location')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        return $routes;
    }

    private function getOverallStats($mountainId)
    {
        $totalMountains = DB::table('mountains')
            ->where('status', 'active')
            ->when($mountainId, fn($q) => $q->where('id', $mountainId))
            ->count();

        $totalUsers = DB::table('users')
            ->where('user_type', 'pendaki')
            ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
            ->count();

        $totalBookings = DB::table('mountain_bookings')
            ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
            ->count();

        $totalRentals = DB::table('mountain_equipment_rentals')
            ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
            ->count();

        $sosToday = DB::table('mountain_sos_signals as sos')
                    ->join('mountain_hiker_status as mhs', 'sos.device_id', '=', 'mhs.device_id')
                    ->when($mountainId, fn($q) => $q->where('mhs.mountain_id', $mountainId))
                    ->whereDate('sos.timestamp', Carbon::today())
                    ->count();

        // $sosToday = 0;

        $activeToday = DB::table('mountain_bookings')
            ->when($mountainId, fn($q) => $q->where('mountain_id', $mountainId))
            ->where('status', 'active')
            ->whereDate('hike_date', '<=', Carbon::today())
            ->whereDate('return_date', '>=', Carbon::today())
            ->count();

        return [
            'total_mountains' => $totalMountains,
            'total_users' => $totalUsers,
            'total_bookings' => $totalBookings,
            'equipment_rentals' => $totalRentals,
            'sos_today' => $sosToday,
            'active_today' => $activeToday,
        ];
    }

    private function getRecentBookings($mountainId)
    {
        return DB::table('mountain_bookings as mb')
            ->join('users as u', 'mb.user_id', '=', 'u.id')
            ->join('mountains as m', 'mb.mountain_id', '=', 'm.id')
            ->select(
                'u.name as user_name',
                'u.email',
                'm.name as mountain_name',
                'mb.team_size',
                'mb.status',
                'mb.hike_date',
                'mb.return_date',
                'mb.created_at'
            )
            ->when($mountainId, fn($q) => $q->where('mb.mountain_id', $mountainId))
            ->orderByDesc('mb.created_at')
            ->limit(8)
            ->get();
    }
}
