<?php

namespace Package\XAuth\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Package\XAuth\Models\Permission;
use Package\XAuth\Models\Role;
use Package\XAuth\Requests\StoreRoleRequest;
use Package\XAuth\Requests\UpdateRoleRequest;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        Permission::check('MANAGE_ROLES');
        return Inertia::render('XAuth/Settings/Role/Index');
    }

    /**
     * Get data to datatables
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getRoles(Request $request)
    {
        Permission::check(['MANAGE_ROLES']);
        return DataTables::of(Role::query())->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        Permission::check('ADD_ROLES');
        $permissions = Permission::select('id', 'name', 'parent_id')->get();
        $model_summary = getModelSummary('roles');
        return Inertia::render('XAuth/Settings/Role/Create', compact('permissions', 'model_summary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRoleRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        try {
            $role = Role::create([
                'name' => $data['name'],
                'display_name' => $data['display_name'],
                'guard_name' => 'web',
            ]);

            if (!empty($data['permissions'])){
                $role = $this->setPermissionToRole($role, $data['permissions']);
            }
            return redirect('/admin/settings/roles')->with('message', ['type' => 'success', 'message' => 'Role created successfully']);
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
        Permission::check('EDIT_ROLES');
        $role = Role::with('permissions')->find($id);
        $permissions = Permission::select('id', 'name', 'parent_id')->get();
        $model_summary = getModelSummary('roles');
        return Inertia::render('XAuth/Settings/Role/Create', compact('role', 'permissions', 'model_summary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRoleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $data = $request->validated();
        $role = Role::find($id);
        if (!$role) return redirect()->back()->with('error', 'Role not found');

        try {
            $role->update([
                'name' => $data['name'],
                'display_name' => $data['display_name'],
            ]);

            if (!empty($data['permissions'])){
                $role = $this->setPermissionToRole($role, $data['permissions']);
            }
            return redirect('/admin/settings/roles')->with('message', ['type' => 'success', 'message' => 'Role updated successfully']);
        } catch (\Exception $e) {
            addErrorToLog($e);
            return redirect()->back()->with('message', ['type' => 'error', 'message' => 'Something went wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Permission::check('DELETE_ROLES');
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return apiResponse('success', 'Role deleted successfully');
        } else {
            return apiResponse('error', 'Role not found');
        }
    }

    /**
     * get comma separated permission ids or names and assign it to given role
     *
     * @param  Role  $role
     * @param  String  $permissions
     * @return Role
     */
    private function setPermissionToRole(Role $role, String $permissions): Role
    {
        $permissions_array = makeArray($permissions);
        if (is_numeric($permissions_array[0])) {
            $permissions_array = Permission::whereIn('id', $permissions_array)->get()->pluck('name');
        }
        $role->syncPermissions($permissions_array);
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return $role;
    }
}
