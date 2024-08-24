<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\MeetingStatus;

class MeetingStatusController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('meeting_status.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.meeting.meeting_status.index');
        
        if($request->ajax()){

            $meeting_statuses = MeetingStatus::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($meeting_statuses)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('meeting_status.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('meeting_statuses.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('meeting_status.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteMeetingStatus(' . $category->id . ')">
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

        return view('admin.meeting.meeting_status.index');
    }

    public function create(Request $request)
    {
        if(!in_array('meeting_status.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.meeting.meeting_status.index');

        return view('admin.meeting.meeting_status.create');
    }

    public function edit(Request $request, $meeting_status_id)
    {
        if(!in_array('meeting_status.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.meeting.meeting_status.index');

        $meeting_status = MeetingStatus::find($meeting_status_id);

        if($meeting_status != null){

            return view('admin.meeting.meeting_status.edit', compact('meeting_status'));
        }
        else{
            return redirect()->route('meeting_statuses.index')->with('error', 'Meeting Status Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:meeting_statuses,name',
        ]);

        $meeting_status = new MeetingStatus();

        $meeting_status->name = $request->name;

        $meeting_status->save();

        return redirect()->route('meeting_statuses.index')->with('success', 'Meeting Status Created Successfully');

    }

    public function update(Request $request, $meeting_status_id)
    {
        $request->validate([
            'name' => 'required|unique:meeting_statuses,name,'.$meeting_status_id
        ]);
        
        $meeting_status = MeetingStatus::find($meeting_status_id);

        if ($meeting_status != null) {

            $meeting_status->name = $request->name;

            $meeting_status->save();

            return redirect()->route('meeting_statuses.index')->with('success', 'Meeting Status Updated Successfully');
        }
        else{
            return redirect()->route('meeting_statuses.index')->with('error', 'Meeting Status Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $meeting_status = MeetingStatus::find($request->meeting_status_id);

            if($meeting_status != null)
            {
                $meeting_status->delete();
                return response()->json(['success' => 'Meeting Status Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Meeting Status Not Found']);
            }
        }
    }

}
