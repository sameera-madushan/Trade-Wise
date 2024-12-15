<?php

namespace Package\XAuth\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Package\XAuth\Models\Permission;
use Package\XAuth\Requests\StorePermissionRequest;
use Package\XAuth\Requests\UpdatePermissionRequest;
use Illuminate\Http\{Request, JsonResponse};
use Yajra\DataTables\DataTables;
use Illuminate\Validation\ValidationException;
use Package\XAuth\Resources\PermissionResource;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        Permission::check('MANAGE_PERMISSIONS');
        return Inertia::render('XAuth/Settings/Permission/Index');
    }

    /**
     * Get data to datatables
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getPermissions(Request $request)
    {
        Permission::check(['MANAGE_PERMISSIONS']);
        $permissions = Permission::with('subPermissions')->whereNull('parent_id')->get();
        return DataTables::of($permissions)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        Permission::check('ADD_PERMISSIONS');
        $parent_permissions = Permission::select('id', 'display_name')->whereNull('parent_id')->get();
        $model_summary = getModelSummary('permissions');
        return Inertia::render('XAuth/Settings/Permission/Create', compact('parent_permissions', 'model_summary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePermissionRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePermissionRequest $request)
    {
        $data = $request->validated();
        $data['guard_name'] = 'web';

        try {
            $permission = Permission::create($data);
            return redirect('/admin/settings/permissions')->with('message', ['type' => 'success', 'message' => 'Permission created successfully']);
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
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        Permission::check('EDIT_PERMISSIONS');
        $permission = Permission::find($id);
        $parent_permissions = Permission::select('id', 'display_name')->whereNull('parent_id')->get();
        $model_summary = getModelSummary('permissions');
        return Inertia::render('XAuth/Settings/Permission/Create', compact('parent_permissions', 'permission', 'model_summary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePermissionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) return redirect()->back()->with('error', 'Permission not found');

        try {
            $permission->update($request->validated());
            return redirect('/admin/settings/permissions')->with('message', ['type' => 'success', 'message' => 'Permission updated successfully']);
        } catch (\Exception $e) {
            addErrorToLog($e);
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Something went wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Permission::check('DELETE_PERMISSIONS');
        $permission = Permission::find($id);
        if ($permission) {
            $permission->delete();
            return redirect('/settings/permissions')->with('success', 'Permission Deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Permission not found');
        }
    }

    /**
     * Get permissions as a resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionsAsResource(Request $request): JsonResponse
    {
        // Permission::check(['MANAGE_PERMISSIONS']);

        $permissions = Permission::query();

        if ($request->has('children') && $request->input('children') != null) {
            if (filter_var($request->input('children'), FILTER_VALIDATE_BOOLEAN)) $permissions->with('subPermissions');
        }

        if ($request->has('parent_only') && $request->input('parent_only') != null) {
            if (filter_var($request->input('parent_only'), FILTER_VALIDATE_BOOLEAN)) $permissions->whereNull('parent_id');
        }

        return apiResponse('success', '', PermissionResource::collection($permissions->get()));
    }
}
