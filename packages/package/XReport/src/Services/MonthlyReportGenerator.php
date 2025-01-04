<?php

namespace Package\XReport\Services;

use Carbon\Carbon;
use Package\XCalendar\Models\Trade;

class MonthlyReportGenerator implements ReportGeneratorInterface
{
    public function generate(array $filters): \Illuminate\Support\Collection
    {
        $date = $filters['date'];
        [$year, $month] = explode('-', $date);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->timestamp * 1000;
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->timestamp * 1000;

        return $this->fetchReportData($startOfMonth, $endOfMonth);
    }

    protected function fetchReportData($startOfMonth, $endOfMonth): \Illuminate\Support\Collection
    {
        return Trade::select('curruncy_pair', 'buy_value', 'buy_price', 'sell_value', 'sell_price', 'bought_amount', 'position', 'pnl', 'timestamp')
        ->whereBetween('timestamp', [$startOfMonth, $endOfMonth])
        ->get()
        ->map(function ($trade) {
            $trade->date = getDateFromTimestamp($trade->timestamp);
            unset($trade->timestamp);
            return $trade;
        });
    }
}
