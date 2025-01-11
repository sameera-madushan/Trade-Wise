<?php

namespace Package\XDashboard\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userIndex()
    {
        return Inertia::render('XAuth/User/Dashboard');
    }
    public function adminIndex()
    {
        return Inertia::render('XAuth/Admin/Dashboard');
    }

    public function getMonthlyPnL()
    {
        $currentYear = now()->year;

        $monthlyPnL = DB::connection('sqlite_user')
            ->table('trades')
            ->selectRaw('
                strftime(\'%Y-%m\', datetime(timestamp / 1000, \'unixepoch\')) as month_year,
                SUM(pnl) as total_pnl,
                SUM(CASE WHEN pnl >= 0 THEN pnl ELSE 0 END) as total_profit,
                SUM(CASE WHEN pnl < 0 THEN pnl ELSE 0 END) as total_loss
            ')
            ->whereNull('deleted_at')
            ->groupByRaw('strftime(\'%Y-%m\', datetime(timestamp / 1000, \'unixepoch\'))')
            ->orderBy('month_year', 'asc')
            ->get();

        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $monthlyPnLData = $monthlyPnL->keyBy('month_year');

        $fullData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthKey = $currentYear . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

            $dataForMonth = $monthlyPnLData->get($monthKey);

            $totalProfit = $dataForMonth->total_profit ?? 0;
            $totalLoss = $dataForMonth->total_loss ?? 0;

            $netPnL = $totalProfit + $totalLoss;

            $fullData[] = [
                'year' => $currentYear,
                'month' => $month,
                'net_pnl' => $netPnL,
            ];

        }

        $chartData = [
            'labels' => collect($fullData)->map(fn($row) => $monthNames[$row['month']] . ' ' . $row['year']),
            'net_pnl' => collect($fullData)->pluck('net_pnl'),
        ];

        return response()->json($chartData);
    }



    public function getDailyPnL()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $dailyPnL = DB::connection('sqlite_user')
        ->table('trades')
        ->selectRaw('
            CAST(strftime(\'%d\', datetime(timestamp / 1000, \'unixepoch\')) AS INTEGER) as day,
            SUM(CASE WHEN pnl >= 0 THEN pnl ELSE 0 END) as total_profit,
            SUM(CASE WHEN pnl < 0 THEN pnl ELSE 0 END) as total_loss
        ')
        ->whereNull('deleted_at')
        ->whereRaw('CAST(strftime(\'%m\', datetime(timestamp / 1000, \'unixepoch\')) AS INTEGER) = ?', [$currentMonth])
        ->whereRaw('CAST(strftime(\'%Y\', datetime(timestamp / 1000, \'unixepoch\')) AS INTEGER) = ?', [$currentYear])
        ->groupByRaw('CAST(strftime(\'%d\', datetime(timestamp / 1000, \'unixepoch\')) AS INTEGER)')
        ->orderBy('day', 'asc')
        ->get()
        ->keyBy('day');

        $fullData = [];
        for ($day = 1; $day <= now()->daysInMonth; $day++) {
            $netPnL = 0;

            if (isset($dailyPnL[$day])) {
                $netPnL = $dailyPnL[$day]->total_profit + $dailyPnL[$day]->total_loss;
            }

            $fullData[] = [
                'day' => $day,
                'net_pnl' => $netPnL,
            ];
        }

        $chartData = [
            'labels' => collect($fullData)->pluck('day'),
            'net_pnl' => collect($fullData)->pluck('net_pnl'),
        ];

        return response()->json($chartData);
    }

    public function getTodayTrades()
    {
        $todayStart = now()->startOfDay()->timestamp * 1000;
        $todayEnd = now()->endOfDay()->timestamp * 1000;

        $todayTrades = DB::connection('sqlite_user')
        ->table('trades')
        ->whereBetween('timestamp', [$todayStart, $todayEnd])
        ->whereNull('deleted_at')
        ->orderBy('timestamp', 'asc')
        ->get(['curruncy_pair', 'pnl']);


        return response()->json($todayTrades);
    }

    public function userCount()
    {
        $userCount = DB::table('users')->count();

        return response()->json($userCount);
    }

}
