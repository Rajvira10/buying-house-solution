<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\MeetingTitle;
use Illuminate\Http\Request;
use App\Models\MeetingMinute;
use App\Models\MeetingStatus;
use App\Http\Controllers\Controller;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('meeting.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.meeting.meetings.index');
        
        if($request->ajax()){

            $meetings = Meeting::orderBy('created_at', 'desc');

            return DataTables::of($meetings)
                ->filter(function ($query) use ($request) {
                    if ($request['search']['value']) {
                        $query->where(function ($query) use ($request) {
                            $query->whereHas('client', function ($query) use ($request) {
                                $query->where('company_name', 'like', "%{$request['search']['value']}%");
                            });
                            $query->orWhereHas('meeting_title', function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request['search']['value']}%");
                            });
                            $query->orWhereHas('meeting_type', function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request['search']['value']}%");
                            });
                            $query->orWhereHas('meeting_status', function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request['search']['value']}%");
                            });
                        })->get();
                    }
                })
                ->addColumn('client', function ($meeting) {
                    return $meeting->client->company_name;
                })
                ->addColumn('meeting_title', function ($meeting) {
                    return $meeting->meeting_title->name;
                })
                ->addColumn('meeting_type', function ($meeting) {
                    return $meeting->meeting_type->name;
                })
                ->addColumn('meeting_status', function ($meeting) {
                    return $meeting->meeting_status->name;
                })
                ->addColumn('date', function ($meeting) {
                    return $meeting->date->format('d/m/Y h:i A');
                })
                
                ->addColumn('action', function ($meeting) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    $edit_button .= '<li><button type="button" class="dropdown-item" onclick="openAddMinuteModal(' . $meeting->id . ')"><i class="ri-add-line align-bottom me-2"></i> Add Minute</button></li>';
                                        
                    $edit_button .= '<li><a href="'.route('meetings.show', $meeting->id).'" class
                    ="dropdown-item"><i class="ri-eye-fill me-2"></i> View</a></li>';

                    if(in_array('meeting.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('meetings.edit', $meeting->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('meeting.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteMeeting(' . $meeting->id . ')">
                                                <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                            </button>
                                        </li>';
                    }

                    // $edit_button .= '<li><button type="button" class="dropdown-item" onclick="openViewMinuteModal(' . $meeting->id . ')"><i class="ri-eye-line align-bottom me-2"></i> View Minute</button></li>';
                    // $edit_button .= '<li><button type="button" class="dropdown-item" onclick="openEditMinuteModal(' . $meeting->id . ')"><i class="ri-edit-line align-bottom me-2"></i> Edit Minute</button></li>';

                    
                    $edit_button .= '</ul></div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.meeting.meeting.index');
    }

    public function create(Request $request)
    {
        if(!in_array('meeting.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.meeting.meetings.index');

        $meeting_titles = MeetingTitle::select('id', 'name')->get();
        $meeting_statuses = MeetingStatus::select('id', 'name')->get();
        $meeting_types = MeetingType::select('id', 'name')->get();
        $clients = Client::select('id', 'company_name')->get();

        $client_id = $request->client_id ?? null;

        return view('admin.meeting.meeting.create', compact('meeting_titles', 'meeting_statuses', 'meeting_types', 'clients', 'client_id'));
    }
    
    public function show(Request $request, $meeting_id)
    {
        if(!in_array('meeting.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.meeting.meetings.index');

        $meeting = Meeting::find($meeting_id);

        if($meeting != null){

            return view('admin.meeting.meeting.show', compact('meeting'));
        }
        else{
            return redirect()->route('meetings.index')->with('error', 'Meeting Not Found');
        }
    }
    
    public function edit(Request $request, $meeting_id)
    {
        if(!in_array('meeting.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.meeting.meetings.index');

        $meeting = Meeting::find($meeting_id);

        if($meeting != null){

            $meeting_statuses = MeetingStatus::select('id', 'name')->get();

            $meeting_types = MeetingType::select('id', 'name')->get();

            $meeting_titles = MeetingTitle::select('id', 'name')->get();

            $clients = Client::select('id', 'company_name')->get();

            return view('admin.meeting.meeting.edit', compact('meeting', 'meeting_statuses', 'meeting_types', 'meeting_titles', 'clients'));
        }
        else{
            return redirect()->route('meetings.index')->with('error', 'Meeting Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'time' => 'required',
            'meeting_type_id' => 'required',
            'meeting_status_id' => 'required',
            'meeting_title_id' => 'required',
            'client_id' => 'required',
            'note' => 'required'
        ]);

        $meeting = new Meeting();

        $meeting->meeting_id = 'MEETING';

        $dateTime = Carbon::createFromFormat('d/m/Y h:i A', $request->date . ' ' . $request->time);
        $meeting->date = $dateTime;

        $meeting->meeting_type_id = $request->meeting_type_id;

        $meeting->meeting_status_id = $request->meeting_status_id;

        $meeting->meeting_title_id = $request->meeting_title_id;

        $meeting->client_id = $request->client_id;

        $meeting->save();

        $meeting->meeting_id = 'MEETING' . $meeting->id;

        $meeting->save();
        

        $meeting_minutes = new MeetingMinute();

        $meeting_minutes->meeting_id = $meeting->id;

        $meeting_minutes->note = $request->note;

        $meeting_minutes->save();

        return redirect()->route('meetings.index')->with('success', 'Meeting Created Successfully');

    }

    public function update(Request $request, $meeting_id)
    {
        $request->validate([
            'date' => 'required',
            'time' => 'required',
            'meeting_type_id' => 'required',
            'meeting_status_id' => 'required',
            'meeting_title_id' => 'required',
            'client_id' => 'required',
            'note' => 'required'
        ]);

        $meeting = Meeting::find($meeting_id);

        if($meeting != null)
        {
            $dateTime = Carbon::createFromFormat('d/m/Y h:i A', $request->date . ' ' . $request->time);
            $meeting->date = $dateTime;

            $meeting->meeting_type_id = $request->meeting_type_id;

            $meeting->meeting_status_id = $request->meeting_status_id;

            $meeting->meeting_title_id = $request->meeting_title_id;

            $meeting->client_id = $request->client_id;

            $meeting->save();

            $meeting_minutes = MeetingMinute::where('meeting_id', $meeting->id)->first();

            $meeting_minutes->note = $request->note;

            $meeting_minutes->save();

            return redirect()->route('meetings.index')->with('success', 'Meeting Updated Successfully');
        }
        else{
            return redirect()->route('meetings.index')->with('error', 'Meeting Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $meeting = Meeting::find($request->meeting_id);

            if($meeting != null)
            {
                $meeting->delete();
                return response()->json(['success' => 'Meeting Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Meeting Not Found']);
            }
        }
    }

    public function getMeetingMinutes($meeting_id)
    {
        $meeting_minutes = MeetingMinute::where('meeting_id', $meeting_id)->first();
        return response()->json($meeting_minutes);
    }


    public function saveMeetingMinutes(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required',
            'note' => 'required'
        ]);

        $meeting_minute = new MeetingMinute();

        $meeting_minute->meeting_id = $request->meeting_id;

        $meeting_minute->note = $request->note;

        $meeting_minute->save();

        return response()->json(['success' => 'Meeting Minutes Saved Successfully']);
    }

}
