<?php

namespace Package\XCalendar\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Package\XCalendar\Models\Trade;
use App\Http\Controllers\Controller;
use Package\XCalendar\Models\TradeNote;
use Yajra\DataTables\Facades\DataTables;
use Package\XCalendar\Requests\StoreTradesRequest;

class CalendarController extends Controller
{
    public function index($timestamp)
    {

        return Inertia::render('XCalendar/XDay/Index', [
            'timestamp' => $timestamp
        ]);
    }

    public function store(StoreTradesRequest $request, $timestamp)
    {

        DB::beginTransaction();

        try {
            $validatedData = $request->validated();
            $validatedData['timestamp'] = $timestamp;

            $newTradeBuyPrice = $validatedData['buy_price'] ?? 0;

            $limitCheck = Trade::isLimitExceeded($timestamp, $newTradeBuyPrice);

            if ($limitCheck) {
                DB::rollBack();
                return redirect()->back()->with('message', ['type' => 'error', 'message' => $limitCheck]);
            }

            if (!empty($validatedData['editing']) && !empty($validatedData['rowId'])) {

                $trade = Trade::find($validatedData['rowId']);

                if ($trade) {

                    $trade->update($validatedData);
                    DB::commit();
                    return redirect()->back()->with('message', ['type' => 'success', 'message' => 'Trade updated successfully']);

                } else {

                    DB::rollBack();
                    return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Trade not found']);
                }
            } else {

                Trade::create($validatedData);

                DB::commit();

                return redirect()->back()->with('message', ['type' => 'success', 'message' => 'Trade saved successfully']);
            }
        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error("Error storing trade: " . $e->getMessage());
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'An error occurred while saving the trade.']);
        }
    }

    public function getTrades($timestamp)
    {
        $trades = Trade::where('timestamp', $timestamp);

        return DataTables::of($trades)
        ->addColumn('pnl_percentage', function ($trade) {
            if ($trade->buy_price != 0) {
                $percentage = round(($trade->pnl / $trade->buy_price) * 100, 2);

                $formattedPercentage = abs($percentage);

                if ($percentage < 0) {
                    return '<span style="color: #e11d48;">' . $formattedPercentage . '%</span>';
                } else {
                    return '<span style="color: #10b981;">' . $formattedPercentage . '%</span>';
                }
            }
            return 'N/A';
        })
        ->rawColumns(['actions', 'pnl_percentage'])
        ->make(true);
    }

    public function deleteTrades($timestamp, $id)
    {
        DB::beginTransaction();

        try {

            $trade = Trade::find($id);

            if (!$trade) {
                DB::rollBack();
                return response()->json([
                    'type' => 'error',
                    'message' => 'Trade not found'
                ], 500);
            }

            $trade->delete();

            DB::commit();

            return response()->json([
                    'type' => 'success',
                    'message' => 'Trade deleted successfully'
                ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error("Error deleting trade: " . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => 'An error occurred while deleting the trade.'
            ], 500);
        }
    }

    public function getPnlsForCalendar()
    {
        // Calculate daily PnL
        $dailyPnls = DB::connection('sqlite_user')
        ->table('trades')
        ->selectRaw('DATE(datetime(timestamp / 1000, "unixepoch")) as trade_date, SUM(pnl) as total_pnl')
        ->whereNull('deleted_at')
        ->groupBy('trade_date')
        ->orderBy('trade_date', 'asc')
        ->get();

        $events = $dailyPnls->map(function ($pnl) {
            return [
                'title' => number_format($pnl->total_pnl, 2),
                'start' => $pnl->trade_date,
                'allDay' => true,
                'color' => $pnl->total_pnl >= 0 ? 'green' : 'red',
            ];
        });

        return response()->json($events);
    }

    public function saveNote($timestamp, $id)
    {
        DB::beginTransaction();

        try {

            $note = TradeNote::where('trade_id', $id)->first();

            if ($note) {

                $note->update([
                    'quill_content' => request('content')
                ]);
            } else {

                TradeNote::create([
                    'trade_id' => $id,
                    'quill_content' => request('content')
                ]);
            }

            DB::commit();

            return redirect()->back()->with('message', ['type' => 'success', 'message' => 'Note saved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error("Error saving the note: " . $e->getMessage());

            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'An error occurred while saving the note.']);
        }
    }


    public function getNote($timestamp, $id)
    {
        $note = TradeNote::where('trade_id', $id)->first();

        if (!$note) {
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Note not found']);
        }

        return response()->json([
            'success' => true,
            'data' => $note->quill_content,
        ]);
    }

}
