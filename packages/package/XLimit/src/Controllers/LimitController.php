<?php

namespace Package\XLimit\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Package\XCalendar\Models\Trade;
use App\Http\Controllers\Controller;
use Package\XLimit\Models\TradeLimit;
use Package\XLimit\Requests\StoreLimitsRequest;

class LimitController extends Controller
{

    public function index()
    {

        return Inertia::render('XLimit/Index');
    }

    public function store(StoreLimitsRequest $request)
    {
        DB::beginTransaction();

        try {

            $validatedData = $request->validated();

            TradeLimit::create($validatedData);

            DB::commit();

            return redirect()->back()->with('message', ['type' => 'success', 'message' => 'Limit created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error("Error storing trade: " . $e->getMessage());
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Error creating limit']);
        }
    }

    public function getLimits()
    {
        $limits = TradeLimit::all();

        $result = [];

        foreach ($limits as $limit) {

            $timestamp = getTimestampFromDate($limit->date);

            $totalBuyPrice = Trade::where('timestamp', $timestamp)
                ->sum('buy_price');

            $percentageUsed = $limit->limit > 0 ? ($totalBuyPrice / $limit->limit) * 100 : 0;

            $result[] = [
                'timestamp' => $timestamp,
                'limit_date' => $limit->date,
                'limit_amount' => $limit->limit,
                'total_buy_price' => $totalBuyPrice,
                'percentage_used' => round($percentageUsed, 2)
            ];
        }

        return response()->json($result);
    }

}
