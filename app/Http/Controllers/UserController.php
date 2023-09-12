<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Alert;
use Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('user.index')) {
            $users = User::where('type', 1)->orderBy('name', 'ASC')->get();
            return view('admin.user.index', compact('users'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        if (auth()->user()->can('user.create')) {
            $roles = Role::all();
            return view('admin.user.create', compact('roles'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (auth()->user()->can('user.create')) {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|max:255|unique:users',
                'username' => 'required|max:255|unique:users',
                'phone' => 'required|max:255|unique:users',
                'image' => 'nullable|image',
                'password' => ['required', 'string', 'min:8'],
            ]);

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->type = 1;
            $user->password = Hash::make($request->password);
            $user->save();
            $role = $request->role;
            $user->syncRoles($role);
            Alert::toast(__('app.messages.user.create'), 'success');
            return redirect()->route('user.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('user.edit')) {
            $user = User::find($id);
            if (!is_null($user)) {
                $roles = Role::all();
                return view('admin.user.edit', compact('user', 'roles'));
            } else {
                Alert::toast(__('app.messages.user.not_found'), 'errror');
                return redirect()->route('user.index');
            }
        } else {
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
    public function update(Request $request, $id) {
        if (auth()->user()->can('user.edit')) {
            $user = User::find($id);
            if (!is_null($user)) {
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                    'email' => 'required|max:255|unique:users,email,' . $user->id,
                    'username' => 'required|max:255|unique:users,username,' . $user->id,
                    'phone' => 'required|max:255|unique:users,phone,' . $user->id,
                    'image' => 'nullable|image',
                ]);

                $user->name = $request->name;
                $user->email = $request->email;
                $user->username = $request->username;
                $user->phone = $request->phone;
                //$user->password = Hash::make($request->password);
                $user->save();
                $role = $request->role;
                $user->syncRoles($role);
                Alert::toast(__('app.messages.user.update'), 'success');
                return redirect()->route('user.index');
            } else {
                Alert::toast(__('app.messages.user.not_found'), 'errror');
                return redirect()->route('user.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('user.delete')) {
            $user = User::find($id);
            if (!is_null($user)) {
                $user->delete();
                Alert::toast(__('app.messages.user.delete'), 'success');
                return redirect()->route('user.index');
            } else {
                Alert::toast(__('app.messages.user.not_found'), 'errror');
                return redirect()->route('user.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function customer_index() {
        if (auth()->user()->can('customer.list')) {
            $customers = User::where('type', 2)->orderBy('id', 'DESC')->paginate(10);
            return view('admin.customer.index', compact('customers'));
        } else {
            session()->flash('error', 'Access Denied !');
            return back();
        }
    }

    public function customer_destroy($id) {
        $customer = User::find($id);
        if (!is_null($customer)) {
            if (File::exists('images/user/' . $customer->image)) {
                File::delete('images/user/' . $customer->image);
            }
            $customer->delete();
            Alert::toast('Customer has been deleted !', 'success');
            return redirect()->route('customer.index');
        } else {
            session()->flash('error', 'Something went wrong !');
            return redirect()->route('customer.index');
        }
    }

    public function customer_password_change(Request $request, $id) {
        $validatedData = $request->validate([
            'password' => 'required|min:8',
        ]);
        $customer = User::find($id);
        if (!is_null($customer)) {
            $customer->password = Hash::make($request->password);
            $customer->save();
            Alert::toast('Password has been changed!', 'success');
            return back();
        } else {
            Alert::toast('Customer Not Found!', 'error');
            return back();
        }
    }

    function customer_status_update(Request $request, $id) {
        $customer = User::find($id);

        $customer->is_active = $request->status;
        $customer->save();

        Alert::toast('Customer status updated successfully!', 'success');
        return back();
    }

    function customer_type_update(Request $request, $id) {
        $customer = User::find($id);

        $customer->is_fraud = $request->is_fraud;
        $customer->save();

        Alert::toast('Customer type updated successfully!', 'success');
        return back();
    }
}
