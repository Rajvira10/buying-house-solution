<?php

namespace App\Http\Controllers\Admin;

use DB;
use DataTables;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('role.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.role.index');

        if($request->ajax()){

            $roles = Role::get();

            return DataTables::of($roles)
                ->addColumn('action', function ($role) {
                    
                    $edit_button = '';
                    
                    if($role->name !== 'super_admin'){
                        
                        if(in_array('role.assign_permission', session('user_permissions')) && in_array('role.edit', session('user_permissions')))
                        {
                            $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="' . route('roles.edit', $role->id) . '" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>
                                            <li>
                                                <a href="' . route('roles.role_permissions', $role->id) . '" class="dropdown-item remove-item-btn">
                                                    <i class="ri-key-fill align-bottom me-2 text-success"></i> Assign Permissions
                                                </a>
                                            </li>
                                        </ul>
                                    </div>';
                        }
                        elseif(in_array('role.assign_permission', session('user_permissions')))
                        {
                            $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="' . route('roles.role_permissions', $role->id) . '" class="dropdown-item remove-item-btn">
                                                    <i class="ri-key-fill align-bottom me-2 text-success"></i> Assign Permissions
                                                </a>
                                            </li>
                                        </ul>
                                    </div>';
                        }
                        elseif(in_array('role.edit', session('user_permissions')))
                        {
                            $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="' . route('roles.edit', $role->id) . '" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>
                                        </ul>
                                    </div>';
                        }
                    }
                    return $edit_button;

                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.role.index');

    }

    public function create(Request $request)
    {
        if(!in_array('role.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.role.index');

        return view('admin.users.role.create');
    }

    public function edit(Request $request, $role_id)
    {
        if(!in_array('role.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.role.index');

        $role = Role::find($role_id);

        if($role != null){

            if($role->editable == false){
                return redirect()->route('roles.index')->with('error', 'This Role is not editable');
            }

            return view('admin.users.role.edit', compact('role'));
        
        }
        else{
            return redirect()->route('roles.index')->with('error', 'Role Not Found');
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:roles,name',
            'description' => 'required'
        ]);
        
        $role = new Role;

        $role->name = $request->name;

        $role->description = $request->description;

        $role->save();

        return redirect()->route('roles.index')->with('success', 'Role Created Successfully');

    }

    public function update(Request $request, $role_id)
    {
        
        $request->validate([
            'name' => 'required|unique:roles,name,' .$role_id,
            'description' => 'required'
        ]);
        
        $role = Role::find($role_id);

        if ($role != null) {

            $role->name = $request->name;

            $role->description = $request->description;

            $role->save();

            return redirect()->route('roles.index')->with('success', 'Role Updated Successfully');
        }
        else{
            return redirect()->route('roles.index')->with('error', 'Role Not Found');
        }

    }

    public function rolePermissions(Request $request, $role_id)
    {
        if(!in_array('role.assign_permission', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.users.role.index');

        $role = Role::find($role_id);

        if($role != null){

            $permissions = Permission::select(
                'id',
                'name',
                'alias',
                'description',
                'permission_group'
            )
            ->get();
                
            $permissions = $permissions->groupBy('permission_group');

            $role_permissions_array = $role->permissions->pluck('id')->toArray();
            
            return view('admin.users.role.rolePermissions', compact('role', 'permissions', 'role_permissions_array'));
        }
        else{
            return redirect()->route('roles.index')->with('error', 'Role Not Found');
        }
    }

    public function assignPermissions(Request $request, $role_id)
    {
        $role = Role::find($role_id);

        if($role != null){
            
            $role->permissions()->sync($request->permissions);
            
            return redirect()->route('roles.index')->with('success', 'Role Permissions Updated Successfully');
        }
        else{
            return redirect()->route('roles.index')->with('error', 'Role Not Found');
        }
    }
}
