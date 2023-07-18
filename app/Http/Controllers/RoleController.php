<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Alert;
use Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('role.index')) {
            $roles = Role::all();
            return view('admin.role.index', compact('roles'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->can('role.create')) {
            $permissions = Permission::all();
            $permissionGroups = User::getPermissionGroups();
            return view('admin.role.create', compact('permissions', 'permissionGroups'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->can('role.create')) {
            $request->validate([
              'name' => 'required|max:100|unique:roles'
            ],[
                'name.unique' => 'This role is already exists'
            ]);
            $name = $request->name;
            
            $role = Role::create(['name' => $request->name]);
            $permissions = $request->input('permissions');
            if (!empty($permissions)) {
              $role->syncPermissions($permissions);
            }
            Alert::toast(__('app.messages.role.create'), 'success');
            return redirect()->route('role.index');
        }
        else
        {
            abort(403, 'Unauthorized action.');
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
        if (auth()->user()->can('role.edit')) {
            $role = Role::findById($id);
            $permissions = Permission::all();
            $permissionGroups = User::getPermissionGroups();
            return view('admin.role.edit', compact('role', 'permissions', 'permissionGroups'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('role.edit')) {
            $role = Role::find($id);
            if (!is_null($role)) {
                $role_name = $request->name;
                $count = Role::where('name', $role_name )
                            ->where('id', '!=', $id)
                            ->count();
                if ($count == 0) {
                    $role->name = $role_name;
                    $role->save();
                    $permissions = $request->input('permissions');
                    if (!empty($permissions)) {
                      $role->syncPermissions($permissions);
                    }
                    Alert::toast(__('app.messages.role.update'), 'success');
                    return back();
                }
                else{
                    Alert::toast(__('app.messages.role.exists'), 'warning');
                    return back();
                }
            }
            else{
                Alert::toast(__('app.messages.role.not_found'), 'success');
                return back();
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
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
        if (auth()->user()->can('role.delete')) {
            $role = Role::find($id);
            if (!is_null($role)) {
                $role->delete();
                Alert::toast(__('app.messages.role.delete'), 'success');
                return back();
            }
            else{
                Alert::toast(__('app.messages.role.not_found'), 'error');
                return back();
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }
}
