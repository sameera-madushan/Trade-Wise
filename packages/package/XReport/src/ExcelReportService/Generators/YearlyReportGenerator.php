<?php

namespace Package\XReport\ExcelReportService\Generators;

use Carbon\Carbon;
use Package\XCalendar\Models\Trade;
use Package\XReport\ExcelReportService\ReportGeneratorInterface;

class YearlyReportGenerator implements ReportGeneratorInterface
{
    public function generate(array $filters): \Illuminate\Support\Collection
    {
        $year = $filters['date'];

        $startOfYear = Carbon::create($year, 1, 1)->startOfYear()->timestamp * 1000;
        $endOfYear = Carbon::create($year, 1, 1)->endOfYear()->timestamp * 1000;

        return $this->fetchReportData($startOfYear, $endOfYear);
    }

    protected function fetchReportData($startOfYear, $endOfYear): \Illuminate\Support\Collection
    {
        return Trade::select('curruncy_pair', 'buy_value', 'buy_price', 'sell_value', 'sell_price', 'bought_amount', 'position', 'pnl', 'timestamp')
        ->whereBetween('timestamp', [$startOfYear, $endOfYear])
        ->get()
        ->map(function ($trade) {
            $trade->date = getDateFromTimestamp($trade->timestamp);
            unset($trade->timestamp);
            return $trade;
        });
    }
}
