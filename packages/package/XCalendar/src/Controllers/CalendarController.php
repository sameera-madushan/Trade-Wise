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
    public function index($uuid, $timestamp)
    {

        return Inertia::render('XCalendar/XDay/Index', [
            'uuid' => $uuid,
            'timestamp' => $timestamp
        ]);
    }

    public function store(StoreTradesRequest $request, $uuid, $timestamp)
    {

        DB::beginTransaction();

        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->id();
            $validatedData['timestamp'] = $timestamp;

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

    public function getTrades($uuid, $timestamp)
    {
        $trades = Trade::where('user_id', auth()->id())->where('timestamp', $timestamp)->get();

        return DataTables::of($trades)
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function deleteTrades($uuid, $timestamp, $id)
    {
        DB::beginTransaction();

        try {

            $trade = Trade::find($id);

            if (!$trade) {
                DB::rollBack();
                return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Trade not found']);
            }

            $trade->delete();

            DB::commit();

            return redirect("/user/$uuid/calendar/$timestamp")
                ->with('message', ['type' => 'success', 'message' => 'Trade deleted successfully'])
                ->setStatusCode(303);

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error("Error deleting trade: " . $e->getMessage());

            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'An error occurred while deleting the trade.']);
        }
    }

    public function getPnlsForCalendar()
    {
        $userId = auth()->id();

        // Calculate daily PnL
        $dailyPnls = DB::table('trades')
            ->selectRaw('DATE(FROM_UNIXTIME(timestamp / 1000)) as trade_date, SUM(pnl) as total_pnl')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->groupBy('trade_date')
            ->orderBy('trade_date', 'asc')
            ->get();

        $events = $dailyPnls->map(function ($pnl) {
            return [
                'title' => 'P&L: ' . number_format($pnl->total_pnl, 2),
                'start' => $pnl->trade_date,
                'allDay' => true,
                'color' => $pnl->total_pnl >= 0 ? 'green' : 'red',
            ];
        });

        return response()->json($events);
    }

    public function saveNote($uuid, $timestamp, $id)
    {
        DB::beginTransaction();

        try {

            $note = TradeNote::where('trade_id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if ($note) {

                $note->update([
                    'quill_content' => request('content')
                ]);
            } else {

                TradeNote::create([
                    'trade_id' => $id,
                    'user_id' => auth()->id(),
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


    public function getNote($uuid, $timestamp, $id)
    {
        $userId = auth()->id();

        $note = TradeNote::where('trade_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$note) {
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Note not found']);
        }

        return response()->json([
            'success' => true,
            'data' => $note->quill_content,
        ]);
    }

}
