<?php

namespace App\Http\Controllers\Admin;

use Hash;
use DataTables;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('user.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.user.index');

        try{
            if($request->ajax()){

                $users = User::with('roles')->where('warehouse_id', session('user_warehouse')->id)
                ->where('type', '=', 'user')
                ->get();
                
                return DataTables::of($users)
                    ->addColumn('name', function($user){
                        return $user->username;
                    })
                    ->addColumn('roles', function ($user) {
                        return $user->roles->pluck('name')->implode(', ');
                    })
                    ->addColumn('action', function ($user) {
                        if(!in_array('user.assign_role', session('user_permissions')))
                        {
                          $edit_button = '<div class="dropdown d-inline-block">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="' . route('users.show', $user->id) . '" class="dropdown-item"><i class="ri-eye-fill eye-icon align-bottom me-2 text-primary"></i> View</a></li>
                            </ul>
                        </div>';   
                        }
                        else{
                            $edit_button = '<div class="dropdown d-inline-block">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="' . route('users.show', $user->id) . '" class="dropdown-item"><i class="ri-eye-fill eye-icon align-bottom me-2 text-primary"></i> View</a></li>
                                <li>
                                    <a href="' . route('users.user_roles', $user->id) . '" class="dropdown-item">
                                        <i class="ri-group-fill align-bottom me-2 text-success"></i> Assign Roles
                                    </a>
                                </li>
                                <li>
                                    <a href="' . route('users.edit', $user->id) . '" class="dropdown-item">
                                        <i class="ri-pencil-line align-bottom me-2 text-warning"></i> Edit
                                    </a>
                                </li>
                            </ul>
                        </div>';
                        }

                        return $edit_button;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
                }

            return view('admin.users.user.index');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function show(Request $request, $user_id)
    {
        if(!in_array('user.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.user.index');
    
        $user = User::find($user_id);

        if($user != null){
            return view('admin.users.user.show', compact('user'));
        }
        else{
            return redirect()->route('users.index')->with('error', 'User Not Found');
        }

    }

    public function create(Request $request)
    {
        if(!in_array('user.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.user.index');
        
        try{
            $warehouses = session('user_warehouse');

            return view('admin.users.user.create', compact('warehouses'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit(Request $request, $user_id)
    {
        if(!in_array('user.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.user.index');
        
        try{
            $user = User::find($user_id);

            return view('admin.users.user.edit', compact('user'));
        
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function store(Request $request)
    {   
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        try{

            $user = new User();

            $user->username = $request->username;
            
            $user->warehouse_id = session('user_warehouse')->id;

            $user->email = $request->email;
            
            $user->password = Hash::make($request->password);
            
            $user->save();

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request, $user_id)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' .$user_id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        try{
            $user = User::find($request->user_id);

            if($user != null){

                $user->username = $request->username;

                $user->email = $request->email;

                if($request->password != null) $user->password = Hash::make($request->password);
                
                $user->save();

                return redirect()->route('users.index')->with('success', 'User updated successfully.');
            }
            else{
                return redirect()->route('users.index')->with('error', 'User not found.');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            
            return redirect()->back()->with('error', $bug);
        }
    }

    public function userRoles(Request $request, $user_id)
    {
        if(!in_array('user.assign_role', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.users.user.index');
        
        try{
            $user = User::find($user_id);

            $roles = Role::select(
                'id',
                'name',
                'description',
            )->get();
            
            $user_roles_array = $user->roles->pluck('id')->toArray();

            return view('admin.users.user.userRoles', compact('roles', 'user_roles_array', 'user'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function assignRoles(Request $request, $user_id)
    {
        $request->session()->now('view_name', 'admin.users.user.index');
        
        try{
            $user = User::find($user_id);

            if($user != null){

                $user->roles()->sync($request->roles);
    
                return redirect()->route('users.index')->with('success', 'User roles assigned successfully.');
            }
            else{
                return redirect()->route('users.index')->with('error', 'User not found.');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}
