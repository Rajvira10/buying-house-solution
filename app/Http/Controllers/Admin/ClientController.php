<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\City;
use App\Models\State;
use App\Models\Client;
use App\Models\Country;
use App\Models\ClientSource;
use App\Models\ClientStatus;
use App\Models\InterestedIn;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('client.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.client.index');
        
        if($request->ajax()){

            $clients = Client::orderBy('created_at', 'desc');

            return DataTables::of($clients)
                ->filter(function ($query) use ($request) {
                    if ($request['search']['value']) {
                        $query->where(function ($query) use ($request) {
                            $query->where('company_name', 'like', '%' . $request['search']['value'] . '%')
                                ->orWhere('contact_no', 'like', '%' . $request['search']['value'] . '%');
                        })->get();
                    }
                })
                ->addColumn('client_source', function ($category) {
                    return $category->client_source->name;
                })

                ->addColumn('business_category', function ($category) {
                    return $category->business_category->name;
                })

                ->addColumn('interested_in', function ($category) {
                    return $category->interested_in->name;
                })

                ->addColumn('client_status', function ($category) {
                    return $category->client_status->name;
                })
                

                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('meeting.create', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('meetings.create', ['client_id' => $category->id]).'" class
                        ="dropdown-item"><i class="ri-calendar-event-line me-2"></i> Add Meeting</a></li>';
                    }
                    
                    if(in_array('call.create', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('calls.create', ['client_id' => $category->id]).'" class
                        ="dropdown-item"><i class="ri-phone-line me-2"></i> Add Call</a></li>';
                    }

                    if(in_array('project.create', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('projects.create', ['client_id' => $category->id]).'" class
                        ="dropdown-item"><i class="ri-folder-add-line me-2"></i> Add Project</a></li>';
                    }

                    if (in_array('client.add_contact_person', session('user_permissions'))) {
                        $edit_button .= '<li><a href="javascript:void(0);" class="dropdown-item" onclick="showAddContactPersonModal(' . $category->id . ')"><i class="ri-user-add-line me-2"></i> Add Contact Person</a></li>';
                    }
                   
                       

                     $edit_button .= '<li><a href="'.route('clients.show', $category->id).'" class
                        ="dropdown-item"><i class="ri-eye-line me-2"></i> View</a></li>';
                        
                    if(in_array('client.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('clients.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('client.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteClient(' . $category->id . ')">
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

        return view('admin.crm.client.index');
    }

    public function create(Request $request)
    {
        if(!in_array('client.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.client.index');

        $countries = Country::all();
        $client_sources = ClientSource::all();
        $business_categories = BusinessCategory::all();
        $interested_ins = InterestedIn::all();
        $client_statuses = ClientStatus::all();

        return view('admin.crm.client.create', compact('countries','client_sources', 'business_categories', 'interested_ins', 'client_statuses'));
    }

    public function show(Request $request, $client_id)
    {
        if(!in_array('client.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.client.index');

        $client = Client::with('client_source', 'business_category', 'interested_in', 'client_status','contact_persons')->find($client_id);

        if($client != null){
            return view('admin.crm.client.show', compact('client'));
        }
        else{
            return redirect()->route('clients.index')->with('error', 'Client Not Found');
        }
    }

    public function edit(Request $request, $client_id)
    {
        if(!in_array('client.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.client.index');

        $client = Client::find($client_id);

        if($client != null){

            $countries = Country::all();
            $client_sources = ClientSource::all();
            $business_categories = BusinessCategory::all();
            $interested_ins = InterestedIn::all();
            $client_statuses = ClientStatus::all();

            return view('admin.crm.client.edit', compact('client', 'countries', 'client_sources', 'business_categories', 'interested_ins', 'client_statuses'));
        }
        else{
            return redirect()->route('clients.index')->with('error', 'Client Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|unique:clients,company_name',
            'contact_no' => 'required',
            'email' => 'nullable|email',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'address' => 'nullable',
            'client_source_id' => 'required|exists:client_sources,id',
            'business_category_id' => 'required|exists:business_categories,id',
            'interested_in_id' => 'required|exists:interested_ins,id',
            'client_status_id' => 'required|exists:client_statuses,id',
            'note' => 'nullable'
        ]);

        $client = new Client();

        $client->company_name = $request->company_name;

        $client->contact_no = $request->contact_no;

        $client->email = $request->email;

        $client->country_id = $request->country_id;

        $client->state_id = $request->state_id;

        $client->city_id = $request->city_id;

        $client->address = $request->address;

        $client->client_source_id = $request->client_source_id;

        $client->business_category_id = $request->business_category_id;
        
        $client->interested_in_id = $request->interested_in_id;

        $client->client_status_id = $request->client_status_id;

        $client->note = $request->note;

        $client->save();

        $client->unique_id = 'CLT' . $client->id;

        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client Created Successfully');

    }

    public function update(Request $request, $client_id)
    {
        $request->validate([
            'company_name' => 'required|unique:clients,company_name,'.$client_id,
            'contact_no' => 'required',
            'email' => 'nullable|email',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'city_id' => 'nullable|exists:cities,id',
            'address' => 'nullable',
            'client_source_id' => 'required|exists:client_sources,id',
            'business_category_id' => 'required|exists:business_categories,id',
            'interested_in_id' => 'required|exists:interested_ins,id',
            'client_status_id' => 'required|exists:client_statuses,id',
            'note' => 'nullable'
        ]);
        
        $client = Client::find($client_id);

        if ($client != null) {

            $client->company_name = $request->company_name;

            $client->contact_no = $request->contact_no;

            $client->email = $request->email;

            $client->country_id = $request->country_id;

            $client->state_id = $request->state_id;

            $client->city_id = $request->city_id;

            $client->address = $request->address;

            $client->client_source_id = $request->client_source_id;

            $client->business_category_id = $request->business_category_id;

            $client->interested_in_id = $request->interested_in_id;

            $client->client_status_id = $request->client_status_id;

            $client->note = $request->note;

            $client->save();

            return redirect()->route('clients.index')->with('success', 'Client Updated Successfully');
        }
        else{
            return redirect()->route('clients.index')->with('error', 'Client Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $client = Client::find($request->client_id);

            if($client != null)
            {
                $client->delete();
                return response()->json(['success' => 'Client Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Client Not Found']);
            }
        }
    }

    public function addContactPerson(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'contact_name' => 'required|string|max:255',
            'contact_designation' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'required|string|max:255',
            'contact_dob' => 'nullable|date',
        ]);

        // Assuming you have a ContactPerson model and relationship set up
        $client = Client::find($validatedData['client_id']);
        $client->contact_persons()->create([
            'name' => $validatedData['contact_name'],
            'designation' => $validatedData['contact_designation'],
            'email' => $validatedData['contact_email'],
            'phone' => $validatedData['contact_phone'],
            'dob' => $validatedData['contact_dob'],
        ]);

        return response()->json(['success' => 'Contact Person Added Successfully']);
    }

    public function getStates(Request $request)
    {
        // dd($states);
        // if($request->ajax())
        // {
            $states = State::where('country_id', $request->country_id)->get();
            return response()->json(['states' => $states]);
        // }
    }

    public function getCities(Request $request)
    {
        // if($request->ajax())
        // {
            $cities = City::where('country_id', $request->country_id)->get();
            if($request->state_id)
            {
                $cities = City::where('state_id', $request->state_id)->get();
            }
            return response()->json(['cities' => $cities]);
        // }
    }

    public function getContactPersons(Request $request, $client_id)
    {
        $client = Client::find($client_id);
        if($client != null)
        {
            $contact_persons = $client->contact_persons;
            return response()->json(['contact_persons' => $contact_persons]);
        }
        else{
            return response()->json(['error' => 'Client Not Found']);
        }
    }
}
