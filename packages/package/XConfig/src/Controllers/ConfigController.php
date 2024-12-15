<?php

namespace Package\XConfig\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Inertia\Inertia;
use Illuminate\Support\Facades\{Log, Auth};
use Illuminate\Validation\Rule;
use Package\XConfig\Models\Config;
use Package\XConfig\Requests\ConfigRequest;
use Package\XConfig\Resources\ConfigResource;
use Yajra\DataTables\DataTables;
use Package\XAuth\Models\Permission;
use Package\XConfig\Models\ConfigCategory;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Permission::check(["MANAGE_CONFIGURATION"]);
        return Inertia::render('XConfig/Settings/Configuration/Index');
    }

    /**
     * Get data to datatables
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getConfig(Request $request)
    {
        Permission::check(['MANAGE_CONFIGURATION']);
        return DataTables::of(Config::with('category'))
            ->addColumn('category', function ($configuration) {
                return $configuration->category->name;
            })
            ->removeColumn('category_id', 'created_by', 'updated_by', 'deleted_at', 'created_at', 'updated_at')
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Permission::check(['MANAGE_CONFIGURATION', 'ADD_CONFIGURATIONS']);
        $config_categories = ConfigCategory::where('status', 1)->get();
        $model_summary = getModelSummary('configurations');
        return Inertia::render('XConfig/Settings/Configuration/Create', compact('config_categories', 'model_summary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ConfigRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfigRequest $request)
    {
        Permission::check(['MANAGE_CONFIGURATION', 'ADD_CONFIGURATIONS']);

        try {
            $config = new Config();
            $config->storeData($request->validated());
            return redirect('/admin/settings/configurations')->with('message', ['type' => 'success', 'message' => 'Configuration created successfully']);
        } catch (\Exception $e) {
            addErrorToLog($e);
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Something went wrong']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Permission::check(['MANAGE_CONFIGURATION', 'EDIT_CONFIGURATIONS']);
        $configuration = Config::find($id);
        if (!$configuration) return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Configuration not found']);
        $config_categories = ConfigCategory::where('status', 1)->get();
        $model_summary = getModelSummary('configurations');
        return Inertia::render('XConfig/Settings/Configuration/Create', compact('configuration', 'config_categories', 'model_summary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ConfigRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ConfigRequest $request, $id)
    {
        Permission::check(['MANAGE_CONFIGURATION', 'EDIT_CONFIGURATIONS']);

        $config = Config::find($id);
        if (!$config) return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Configuration not found']);

        try {
            $config->storeData($request->validated());
            return redirect('/admin/settings/configurations')->with('message', ['type' => 'success', 'message' => 'Configuration updated successfully']);
        } catch (\Exception $e) {
            addErrorToLog($e);
            return apiResponse('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
