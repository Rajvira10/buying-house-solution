<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\MeetingType;

class MeetingTypeController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('meeting_type.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.meeting.meeting_type.index');
        
        if($request->ajax()){

            $meeting_types = MeetingType::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($meeting_types)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('meeting_type.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('meeting_types.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('meeting_type.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteMeetingType(' . $category->id . ')">
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

        return view('admin.meeting.meeting_type.index');
    }

    public function create(Request $request)
    {
        if(!in_array('meeting_type.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.meeting.meeting_type.index');

        return view('admin.meeting.meeting_type.create');
    }

    public function edit(Request $request, $meeting_type_id)
    {
        if(!in_array('meeting_type.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.meeting.meeting_type.index');

        $meeting_type = MeetingType::find($meeting_type_id);

        if($meeting_type != null){

            return view('admin.meeting.meeting_type.edit', compact('meeting_type'));
        }
        else{
            return redirect()->route('meeting_types.index')->with('error', 'Meeting Type Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:meeting_types,name',
        ]);

        $meeting_type = new MeetingType();

        $meeting_type->name = $request->name;

        $meeting_type->save();

        return redirect()->route('meeting_types.index')->with('success', 'Meeting Type Created Successfully');

    }

    public function update(Request $request, $meeting_type_id)
    {
        $request->validate([
            'name' => 'required|unique:meeting_types,name,'.$meeting_type_id
        ]);
        
        $meeting_type = MeetingType::find($meeting_type_id);

        if ($meeting_type != null) {

            $meeting_type->name = $request->name;

            $meeting_type->save();

            return redirect()->route('meeting_types.index')->with('success', 'Meeting Type Updated Successfully');
        }
        else{
            return redirect()->route('meeting_types.index')->with('error', 'Meeting Type Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $meeting_type = MeetingType::find($request->meeting_type_id);

            if($meeting_type != null)
            {
                $meeting_type->delete();
                return response()->json(['success' => 'Meeting Type Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Meeting Type Not Found']);
            }
        }
    }

}
