<?php

namespace Package\XDashboard\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($uuid)
    {
        return Inertia::render('XAuth/User/Dashboard', [
            'uuid' => $uuid,
        ]);
    }

    public function getMonthlyPnL()
    {
        $currentYear = now()->year;

        $monthlyPnL = DB::table('trades')
            ->selectRaw('
                MONTH(FROM_UNIXTIME(timestamp / 1000)) as month,
                SUM(pnl) as total_pnl,
                SUM(CASE WHEN pnl >= 0 THEN pnl ELSE 0 END) as total_profit,
                SUM(CASE WHEN pnl < 0 THEN pnl ELSE 0 END) as total_loss
            ')
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->whereRaw('YEAR(FROM_UNIXTIME(timestamp / 1000)) = ?', [$currentYear])
            ->groupByRaw('MONTH(FROM_UNIXTIME(timestamp / 1000))')
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy('month');

        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $fullData = [];
        for ($month = 1; $month <= 12; $month++) {
            $totalProfit = $monthlyPnL[$month]->total_profit ?? 0;
            $totalLoss = $monthlyPnL[$month]->total_loss ?? 0;

            $fullData[] = [
                'year' => $currentYear,
                'month' => $month,
                'total_profit' => $totalProfit,
                'total_loss' => $totalLoss,
            ];
        }

        $chartData = [
            'labels' => collect($fullData)->map(fn($row) => $monthNames[$row['month']] . ' ' . $row['year']),
            'profits' => collect($fullData)->pluck('total_profit'),
            'losses' => collect($fullData)->pluck('total_loss'),
        ];

        return response()->json($chartData);
    }

    public function getDailyPnL()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $dailyPnL = DB::table('trades')
            ->selectRaw('
                DAY(FROM_UNIXTIME(timestamp / 1000)) as day,
                SUM(CASE WHEN pnl >= 0 THEN pnl ELSE 0 END) as total_profit,
                SUM(CASE WHEN pnl < 0 THEN pnl ELSE 0 END) as total_loss
            ')
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->whereRaw('MONTH(FROM_UNIXTIME(timestamp / 1000)) = ?', [$currentMonth])
            ->whereRaw('YEAR(FROM_UNIXTIME(timestamp / 1000)) = ?', [$currentYear])
            ->groupByRaw('DAY(FROM_UNIXTIME(timestamp / 1000))')
            ->orderBy('day', 'asc')
            ->get()
            ->keyBy('day');

        $fullData = [];
        for ($day = 1; $day <= now()->daysInMonth; $day++) {
            $fullData[] = [
                'day' => $day,
                'total_profit' => $dailyPnL[$day]->total_profit ?? 0,
                'total_loss' => $dailyPnL[$day]->total_loss ?? 0,
            ];
        }

        $chartData = [
            'labels' => collect($fullData)->pluck('day'),
            'profits' => collect($fullData)->pluck('total_profit'),
            'losses' => collect($fullData)->pluck('total_loss'),
        ];

        return response()->json($chartData);
    }


}
