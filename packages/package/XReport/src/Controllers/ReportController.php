<?php

namespace Package\XReport\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Package\XCalendar\Models\Trade;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Yajra\DataTables\Facades\DataTables;
use Package\XReport\ExcelReportService\ReportContext;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Package\XReport\ExcelReportService\Generators\DailyReportGenerator;
use Package\XReport\ExcelReportService\Generators\YearlyReportGenerator;
use Package\XReport\ExcelReportService\Generators\MonthlyReportGenerator;

class ReportController extends Controller
{
    public function indexDailyReport()
    {
        return Inertia::render('XReport/Daily');
    }

    public function dailyReportData(Request $request, $date)
    {
        $startOfDay = Carbon::parse($date)->startOfDay()->timestamp * 1000;
        $endOfDay = Carbon::parse($date)->endOfDay()->timestamp * 1000;

        $query = Trade::whereBetween('timestamp', [$startOfDay, $endOfDay]);

        return DataTables::of($query)
            ->make(true);
    }

    public function indexMonthlyReport()
    {
        return Inertia::render('XReport/Monthly');
    }

    public function monthlyReportData(Request $request, $date)
    {
        [$year, $month] = explode('-', $date);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->timestamp * 1000;
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->timestamp * 1000;

        $query = Trade::whereBetween('timestamp', [$startOfMonth, $endOfMonth]);

        return DataTables::of($query)
            ->make(true);
    }


    public function indexYearlyReport()
    {
        return Inertia::render('XReport/Yearly');
    }

    public function yearlyReportData(Request $request, $year)
    {
        $startOfYear = Carbon::create($year, 1, 1)->startOfYear()->timestamp * 1000;
        $endOfYear = Carbon::create($year, 1, 1)->endOfYear()->timestamp * 1000;

        $query = Trade::whereBetween('timestamp', [$startOfYear, $endOfYear]);

        return DataTables::of($query)
            ->make(true);
    }

    public function exportReport(Request $request)
    {

        $type = $request->input('type');
        $date = $request->input('date');

        $context = new ReportContext();

        switch ($type) {
            case 'yearly':
                $context->setReportGenerator(new YearlyReportGenerator());
                break;
            case 'monthly':
                $context->setReportGenerator(new MonthlyReportGenerator());
                break;
            default:
                $context->setReportGenerator(new DailyReportGenerator());
                break;
        }

        $filters = ['date' => $date];

        $data = $context->generateReport($filters);

        $dataArray = $data->toArray();

        $filename = $type . '_report_' . $date . '.xlsx';

        // Create a StreamedResponse for downloading the file
        return new StreamedResponse(function () use ($dataArray) {
            (new FastExcel($dataArray))->export('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

    }

}
