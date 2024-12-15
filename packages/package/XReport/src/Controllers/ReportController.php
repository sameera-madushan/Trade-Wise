<?php

namespace Package\XReport\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Package\XCalendar\Models\Trade;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function indexDailyReport($uuid)
    {
        return Inertia::render('XReport/Daily', [
            'uuid' => $uuid,
        ]);
    }

    public function dailyReportData(Request $request, $uuid, $date)
    {
        $startOfDay = Carbon::parse($date)->startOfDay()->timestamp * 1000;
        $endOfDay = Carbon::parse($date)->endOfDay()->timestamp * 1000;

        $query = Trade::where('user_id', auth()->id())
            ->whereBetween('timestamp', [$startOfDay, $endOfDay]);

        return DataTables::of($query)
            ->make(true);
    }

    public function indexMonthlyReport()
    {
        return Inertia::render('XReport/Monthly');
    }

    public function monthlyReportData(Request $request, $uuid, $date)
    {
        [$year, $month] = explode('-', $date);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->timestamp * 1000;
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->timestamp * 1000;

        $query = Trade::where('user_id', auth()->id())
            ->whereBetween('timestamp', [$startOfMonth, $endOfMonth]);

        return DataTables::of($query)
            ->make(true);
    }


    public function indexYearlyReport()
    {
        return Inertia::render('XReport/Yearly');
    }

    public function yearlyReportData(Request $request, $uuid, $year)
    {
        $startOfYear = Carbon::create($year, 1, 1)->startOfYear()->timestamp * 1000;
        $endOfYear = Carbon::create($year, 1, 1)->endOfYear()->timestamp * 1000;

        $query = Trade::where('user_id', auth()->id())
            ->whereBetween('timestamp', [$startOfYear, $endOfYear]);

        return DataTables::of($query)
            ->make(true);
    }


}
