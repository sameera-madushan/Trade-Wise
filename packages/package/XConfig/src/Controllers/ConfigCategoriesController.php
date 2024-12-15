<?php

namespace Package\XConfig\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Inertia\Inertia;
use Package\XAuth\Models\Permission;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Package\XConfig\Models\ConfigCategory;
use Package\XConfig\Requests\ConfigCategoryRequest;
use Package\XConfig\Resources\ConfigCategoriesResource;

class ConfigCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Permission::check(['MANAGE_CONFIGURATION_CATEGORIES']);
        return Inertia::render('XConfig/Settings/Configuration/Category/Index');
    }

    /**
     * Get data to datatables
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getConfigCategories(Request $request)
    {
        Permission::check(['MANAGE_CONFIGURATION_CATEGORIES']);
        return DataTables::of(ConfigCategory::query())->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Permission::check(['MANAGE_CONFIGURATION_CATEGORIES', 'ADD_CONFIGURATION_CATEGORIES']);
        $model_summary = getModelSummary('configuration_category');
        return Inertia::render('XConfig/Settings/Configuration/Category/Create', compact('model_summary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ConfigCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfigCategoryRequest $request)
    {
        Permission::check(['MANAGE_CONFIGURATION_CATEGORIES', 'ADD_CONFIGURATION_CATEGORIES']);
        $data = $request->validated();

        try {
            $configcategory = ConfigCategory::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'status' => $data['status'] ? 1 : 0,
            ]);

            return redirect('/admin/settings/config-categories')->with('message', ['type' => 'success', 'message' => 'Configuration category created successfully']);
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
        Permission::check(['MANAGE_CONFIGURATION_CATEGORIES', 'EDIT_CONFIGURATION_CATEGORIES']);
        $configcategory = ConfigCategory::find($id);
        $model_summary = getModelSummary('configuration_category');
        return Inertia::render('XConfig/Settings/Configuration/Category/Create', compact('configcategory', 'model_summary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ConfigCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ConfigCategoryRequest $request, $id)
    {
        Permission::check(['MANAGE_CONFIGURATION_CATEGORIES', 'EDIT_CONFIGURATION_CATEGORIES']);
        $data = $request->validated();

        $configcategory = ConfigCategory::find($id);
        if (!$configcategory) return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Category not found']);

        try {
            $configcategory->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'status' => $data['status'] ? 1 : 0,
            ]);

            return redirect('/admin/settings/config-categories')->with('message', ['type' => 'success', 'message' => 'Configuration category updated successfully']);
        } catch (\Exception $e) {
            addErrorToLog($e);
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Something went wrong']);
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
