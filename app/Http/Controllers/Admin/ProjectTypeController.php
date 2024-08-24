<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\ProjectType;

class ProjectTypeController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('project_type.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.project.project_type.index');
        
        if($request->ajax()){

            $project_types = ProjectType::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($project_types)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('project_type.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('project_types.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('project_type.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteProjectType(' . $category->id . ')">
                                                <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                            </button>
                                        </li>';
                    }
                    $edit_button .= '</ul></div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.project.project_type.index');
    }

    public function create(Request $request)
    {
        if(!in_array('project_type.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.project.project_type.index');

        return view('admin.project.project_type.create');
    }

    public function edit(Request $request, $project_type_id)
    {
        if(!in_array('project_type.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.project.project_type.index');

        $project_type = ProjectType::find($project_type_id);

        if($project_type != null){

            return view('admin.project.project_type.edit', compact('project_type'));
        }
        else{
            return redirect()->route('project_types.index')->with('error', 'Project Type Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:project_types,name',
        ]);

        $project_type = new ProjectType();

        $project_type->name = $request->name;

        $project_type->save();

        return redirect()->route('project_types.index')->with('success', 'Project Type Created Successfully');

    }

    public function update(Request $request, $project_type_id)
    {
        $request->validate([
            'name' => 'required|unique:project_types,name,'.$project_type_id
        ]);
        
        $project_type = ProjectType::find($project_type_id);

        if ($project_type != null) {

            $project_type->name = $request->name;

            $project_type->save();

            return redirect()->route('project_types.index')->with('success', 'Project Type Updated Successfully');
        }
        else{
            return redirect()->route('project_types.index')->with('error', 'Project Type Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $project_type = ProjectType::find($request->project_type_id);

            if($project_type != null)
            {
                $project_type->delete();
                return response()->json(['success' => 'Project Type Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Project Type Not Found']);
            }
        }
    }

}
