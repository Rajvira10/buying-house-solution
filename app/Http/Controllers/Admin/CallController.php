<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Call;
use App\Models\Client;
use App\Models\CallType;
use App\Models\CallStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CallController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('call.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.call.calls.index');
        
        if($request->ajax()){

            $calls = Call::orderBy('created_at', 'desc');

            return DataTables::of($calls)
                ->filter(function ($query) use ($request) {
                    if ($request['search']['value']) {
                        $query->where(function ($query) use ($request) {
                            $query->whereHas('client', function ($query) use ($request) {
                                $query->where('company_name', 'like', "%{$request['search']['value']}%");
                            });
                            $query->orWhereHas('contact_person', function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request['search']['value']}%");
                            });
                            $query->orWhereHas('user', function ($query) use ($request) {
                                $query->where('first_name', 'like', "%{$request['search']['value']}%");
                                $query->orWhere('last_name', 'like', "%{$request['search']['value']}%");
                            });
                        })->get();
                    }
                })
                ->addColumn('client', function ($call) {
                    return $call->client->company_name;
                })
                ->addColumn('contact_person', function ($call) {
                    return $call->contact_person->name ?? '';
                })
                ->addColumn('call_type', function ($call) {
                    return $call->call_type->name;
                })
                ->addColumn('call_status', function ($call) {
                    return $call->call_status->name;
                })
                ->addColumn('called_by', function ($call) {
                    return $call->user->first_name . ' ' . $call->user->last_name;
                })
                ->addColumn('date', function ($call) {
                    return Carbon::parse($call->call_date)->format('d M Y h:i A');
                })
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    
                        $edit_button .= '<li><a href="'.route('calls.show', $category->id).'" class
                        ="dropdown-item"><i class="ri-eye-fill me-2"></i> Show</a></li>';

                    if(in_array('call.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('calls.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }

                    if(in_array('call.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteCall(' . $category->id . ')">
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

        return view('admin.call.calls.index');
    }

    public function create(Request $request)
    {
        if(!in_array('call.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.call.calls.index');
        
        $call_types = CallType::select('id', 'name')->get();
        $call_statuses = CallStatus::select('id', 'name')->get();
        $clients = Client::select('id', 'company_name')->get();

        $client = $request->client_id ?? null;

        return view('admin.call.calls.create', compact('call_types', 'call_statuses', 'clients', 'client'));
    }

    public function edit(Request $request, $call_id)
    {
        if(!in_array('call.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.call.calls.index');

        $call = Call::find($call_id);
        $call_types = CallType::select('id', 'name')->get();
        $call_statuses = CallStatus::select('id', 'name')->get();
        $clients = Client::select('id', 'company_name')->get();

        if($call != null){

            return view('admin.call.calls.edit', compact('call', 'call_types', 'call_statuses', 'clients'));
        }
        else{
            return redirect()->route('calls.index')->with('error', 'Call Not Found');
        }
    }

    public function show(Request $request, $call_id)
    {
        if(!in_array('call.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.call.calls.index');

        $call = Call::find($call_id);

        if($call != null){

            return view('admin.call.calls.show', compact('call'));
        }
        else{
            return redirect()->route('calls.index')->with('error', 'Call Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'contact_person_id' => 'nullable',
            'call_type_id' => 'required',
            'call_status_id' => 'required',
            'call_summary' => 'nullable',
        ]);

        $call = new Call();

        $call->client_id = $request->client_id;
        $call->contact_person_id = $request->contact_person_id;
        $call->call_type_id = $request->call_type_id;
        $call->call_status_id = $request->call_status_id;
        $call->call_summary = $request->call_summary;
        $call->user_id = auth()->user()->id;
        $call->call_date = now();
        $call->save();

        return redirect()->route('calls.index')->with('success', 'Call Created Successfully');

    }

    public function update(Request $request, $call_id)
    {
        $request->validate([
            'client_id' => 'required',
            'contact_person_id' => 'nullable',
            'call_type_id' => 'required',
            'call_status_id' => 'required',
            'call_summary' => 'nullable',
        ]);

        $call = Call::find($call_id);

        if($call != null)
        {
            $call->client_id = $request->client_id;
            $call->contact_person_id = $request->contact_person_id;
            $call->call_type_id = $request->call_type_id;
            $call->call_status_id = $request->call_status_id;
            $call->call_summary = $request->call_summary;
            $call->save();

            return redirect()->route('calls.index')->with('success', 'Call Updated Successfully');
        }
        else{
            return redirect()->route('calls.index')->with('error', 'Call Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $call = Call::find($request->call_id);

            if($call != null)
            {
                $call->delete();
                return response()->json(['success' => 'Call Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Call Not Found']);
            }
        }
    }
    
}
