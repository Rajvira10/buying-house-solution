<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\CallStatus;

class CallStatusController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('call_status.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.call.call_status.index');
        
        if($request->ajax()){

            $call_statuses = CallStatus::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($call_statuses)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('call_status.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('call_statuses.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('call_status.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteCallStatus(' . $category->id . ')">
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

        return view('admin.call.call_status.index');
    }

    public function create(Request $request)
    {
        if(!in_array('call_status.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.call.call_status.index');

        return view('admin.call.call_status.create');
    }

    public function edit(Request $request, $call_status_id)
    {
        if(!in_array('call_status.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.call.call_status.index');

        $call_status = CallStatus::find($call_status_id);

        if($call_status != null){

            return view('admin.call.call_status.edit', compact('call_status'));
        }
        else{
            return redirect()->route('call_statuses.index')->with('error', 'Call Status Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:call_statuses,name',
        ]);

        $call_status = new CallStatus();

        $call_status->name = $request->name;

        $call_status->save();

        return redirect()->route('call_statuses.index')->with('success', 'Call Status Created Successfully');

    }

    public function update(Request $request, $call_status_id)
    {
        $request->validate([
            'name' => 'required|unique:call_statuses,name,'.$call_status_id
        ]);
        
        $call_status = CallStatus::find($call_status_id);

        if ($call_status != null) {

            $call_status->name = $request->name;

            $call_status->save();

            return redirect()->route('call_statuses.index')->with('success', 'Call Status Updated Successfully');
        }
        else{
            return redirect()->route('call_statuses.index')->with('error', 'Call Status Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $call_status = CallStatus::find($request->call_status_id);

            if($call_status != null)
            {
                $call_status->delete();
                return response()->json(['success' => 'Call Status Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Call Status Not Found']);
            }
        }
    }

}
