<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\ProjectStatus;

class ProjectStatusController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('project_status.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.project.project_status.index');
        
        if($request->ajax()){

            $project_statuses = ProjectStatus::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($project_statuses)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('project_status.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('project_statuses.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('project_status.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteProjectStatus(' . $category->id . ')">
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

        return view('admin.project.project_status.index');
    }

    public function create(Request $request)
    {
        if(!in_array('project_status.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.project.project_status.index');

        return view('admin.project.project_status.create');
    }

    public function edit(Request $request, $project_status_id)
    {
        if(!in_array('project_status.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.project.project_status.index');

        $project_status = ProjectStatus::find($project_status_id);

        if($project_status != null){

            return view('admin.project.project_status.edit', compact('project_status'));
        }
        else{
            return redirect()->route('project_statuses.index')->with('error', 'Project Status Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:project_statuses,name',
        ]);

        $project_status = new ProjectStatus();

        $project_status->name = $request->name;

        $project_status->save();

        return redirect()->route('project_statuses.index')->with('success', 'Project Status Created Successfully');

    }

    public function update(Request $request, $project_status_id)
    {
        $request->validate([
            'name' => 'required|unique:project_statuses,name,'.$project_status_id
        ]);
        
        $project_status = ProjectStatus::find($project_status_id);

        if ($project_status != null) {

            $project_status->name = $request->name;

            $project_status->save();

            return redirect()->route('project_statuses.index')->with('success', 'Project Status Updated Successfully');
        }
        else{
            return redirect()->route('project_statuses.index')->with('error', 'Project Status Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $project_status = ProjectStatus::find($request->project_status_id);

            if($project_status != null)
            {
                $project_status->delete();
                return response()->json(['success' => 'Project Status Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Project Status Not Found']);
            }
        }
    }

}
