<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('department.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.department.index');
        
        if($request->ajax()){

            $departments = Department::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($departments)
                ->addColumn('action', function ($category) {

                    $edit_button = '';

                    if(in_array('department.edit', session('user_permissions')))
                    {
                        $edit_button = '<a href="' . route('departments.edit', $category->id) . '">
                                        <button class="btn btn-sm btn-success edit-item-btn">
                                            Edit
                                        </button>
                                    </a>';
                    }
                    
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.hrm.department.index');
    }

    public function create(Request $request)
    {
        if(!in_array('department.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.department.index');

        return view('admin.hrm.department.create');
    }

    public function edit(Request $request, $department_id)
    {
        if(!in_array('department.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.department.index');

        $department = Department::find($department_id);

        if($department != null){

            if($department->editable == false){
                return redirect()->route('departments.index')->with('error', 'This Department is not editable');
            }

            return view('admin.hrm.department.edit', compact('department'));
        }
        else{
            return redirect()->route('departments.index')->with('error', 'Department Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments,name',
        ]);

        $department = new Department();

        $department->name = $request->name;

        $department->save();

        return redirect()->route('departments.index')->with('success', 'Department Created Successfully');

    }

    public function update(Request $request, $department_id)
    {
        $request->validate([
            'name' => 'required|unique:departments,name,'.$department_id
        ]);
        
        $department = Department::find($department_id);

        if ($department != null) {

            $department->name = $request->name;

            $department->save();

            return redirect()->route('departments.index')->with('success', 'Department Updated Successfully');
        }
        else{
            return redirect()->route('departments.index')->with('error', 'Department Not Found');
        }
    }

}
