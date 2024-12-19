<?php

namespace Package\XLimit\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Package\XLimit\Models\TradeLimit;
use Yajra\DataTables\Facades\DataTables;
use Package\XLimit\Requests\StoreLimitsRequest;

class LimitController extends Controller
{
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

        return DataTables::of($limits)
        ->rawColumns(['actions'])
        ->make(true);
    }
}
