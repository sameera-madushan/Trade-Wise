<?php

namespace Package\XAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Package\XAuth\Models\Permission;
use Package\XAuth\Models\Role;
use Package\XAuth\Models\User;
use Package\XAuth\Requests\StoreUserRequest;
use Package\XAuth\Requests\UpdateUserRequest;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        Permission::check('MANAGE_USERS');
        return Inertia::render('XAuth/Settings/User/Index');
    }

    public function getUsers(Request $request)
    {
        Permission::check('MANAGE_USERS');
        $users = DB::table('users')->select('id', 'name', 'email')->get();
        return DataTables::of($users)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        Permission::check('ADD_USERS');
        $roles = Role::select('id', 'name', 'display_name')->get();
        $model_summary = getModelSummary('users');
        return Inertia::render('XAuth/Settings/User/Create', compact('roles', 'model_summary'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::createUser($request->safe()->only(['name', 'email', 'password']));


            try {
                $data = $request->validated();
                // dd($data);
                Mail::to($data['email'], $data['name'])->send(new class ($data) extends Mailable {
                    public function __construct($data)
                    {
                        $this->data = $data;
                    }

                    public function build()
                    {
                        $appName = Config::get('app.name');
                        return $this->view('emails.login_info')->with([
                            'data' => $this->data
                        ])->subject("Welcome to " . $appName);
                    }
                });
            } catch (\Exception $e) {
                //throw $th;
                addErrorToLog($e);

            }

            if (!empty($request->validated('roles'))) {
                $roles_array = makeArray($request->safe()->only(['roles']));
                $user->assignRole($roles_array);
            }
            return redirect('/admin/settings/users')->with('message', ['type' => 'success', 'message' => 'User created successfully']);
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
        Permission::check('EDIT_USERS');
        $user = User::find($id);
        $user->user_roles = $user->roles->pluck('name')->toArray();
        $roles = Role::select('id', 'name', 'display_name')->get();
        $model_summary = getModelSummary('users');
        return Inertia::render('XAuth/Settings/User/Create', compact('roles', 'user', 'model_summary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {

        try {
            $user = User::find($id);
            if (!$user) {
                redirect()->back()->with('message', ['type' => 'error', 'message' => 'User not found']);
            }
            $user->name = $request->validated('name');
            $user->email = $request->validated('email');
            if (!empty($request->validated('password'))) {
                $user->password = Hash::make($request->validated('password'));
            }
            $user->save();

            if (!empty($request->validated('roles'))) {
                $roles_array = makeArray($request->validated('roles'));
                $user->syncRoles($roles_array);
            }
            return redirect('/admin/settings/users')->with('message', ['type' => 'success', 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            addErrorToLog($e);
            redirect()->back()->with('message', ['type' => 'error', 'message' => 'Something went wrong']);
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
        Permission::check('DELETE_USERS');
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect('/settings/users')->with('success', 'Permission Deleted successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }
}
