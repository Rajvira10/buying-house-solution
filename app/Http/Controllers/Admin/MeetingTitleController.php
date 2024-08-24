<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\MeetingTitle;

class MeetingTitleController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('meeting_title.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.meeting.meeting_title.index');
        
        if($request->ajax()){

            $meeting_titles = MeetingTitle::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($meeting_titles)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('meeting_title.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('meeting_titles.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('meeting_title.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteMeetingTitle(' . $category->id . ')">
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

        return view('admin.meeting.meeting_title.index');
    }

    public function create(Request $request)
    {
        if(!in_array('meeting_title.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.meeting.meeting_title.index');

        return view('admin.meeting.meeting_title.create');
    }

    public function edit(Request $request, $meeting_title_id)
    {
        if(!in_array('meeting_title.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.meeting.meeting_title.index');

        $meeting_title = MeetingTitle::find($meeting_title_id);

        if($meeting_title != null){

            return view('admin.meeting.meeting_title.edit', compact('meeting_title'));
        }
        else{
            return redirect()->route('meeting_titles.index')->with('error', 'Meeting Title Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:meeting_titles,name',
        ]);

        $meeting_title = new MeetingTitle();

        $meeting_title->name = $request->name;

        $meeting_title->save();

        return redirect()->route('meeting_titles.index')->with('success', 'Meeting Title Created Successfully');

    }

    public function update(Request $request, $meeting_title_id)
    {
        $request->validate([
            'name' => 'required|unique:meeting_titles,name,'.$meeting_title_id
        ]);
        
        $meeting_title = MeetingTitle::find($meeting_title_id);

        if ($meeting_title != null) {

            $meeting_title->name = $request->name;

            $meeting_title->save();

            return redirect()->route('meeting_titles.index')->with('success', 'Meeting Title Updated Successfully');
        }
        else{
            return redirect()->route('meeting_titles.index')->with('error', 'Meeting Title Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $meeting_title = MeetingTitle::find($request->meeting_title_id);

            if($meeting_title != null)
            {
                $meeting_title->delete();
                return response()->json(['success' => 'Meeting Title Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Meeting Title Not Found']);
            }
        }
    }

}
