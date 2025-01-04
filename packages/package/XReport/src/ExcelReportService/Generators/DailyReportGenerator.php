<?php

namespace Package\XReport\ExcelReportService\Generators;

use Carbon\Carbon;
use Package\XCalendar\Models\Trade;
use Package\XReport\ExcelReportService\ReportGeneratorInterface;

class DailyReportGenerator implements ReportGeneratorInterface
{
    public function generate(array $filters): \Illuminate\Support\Collection
    {
        $date = $filters['date'];

        $startOfDay = Carbon::parse($date)->startOfDay()->timestamp * 1000;
        $endOfDay = Carbon::parse($date)->endOfDay()->timestamp * 1000;

        return $this->fetchReportData($startOfDay, $endOfDay);
    }

    protected function fetchReportData($startOfDay, $endOfDay): \Illuminate\Support\Collection
    {
        return Trade::select('curruncy_pair', 'buy_value', 'buy_price', 'sell_value', 'sell_price', 'bought_amount', 'position', 'pnl', 'timestamp')
        ->whereBetween('timestamp', [$startOfDay, $endOfDay])
        ->get()
        ->map(function ($trade) {
            $trade->date = getDateFromTimestamp($trade->timestamp);
            unset($trade->timestamp);
            return $trade;
        });
    }
}
