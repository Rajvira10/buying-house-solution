<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\ClientStatus;

class ClientStatusController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('client_status.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.client_status.index');
        
        if($request->ajax()){

            $client_statuses = ClientStatus::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($client_statuses)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('client_status.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('client_statuses.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('client_status.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteClientStatus(' . $category->id . ')">
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

        return view('admin.crm.client_status.index');
    }

    public function create(Request $request)
    {
        if(!in_array('client_status.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.client_status.index');

        return view('admin.crm.client_status.create');
    }

    public function edit(Request $request, $client_status_id)
    {
        if(!in_array('client_status.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.client_status.index');

        $client_status = ClientStatus::find($client_status_id);

        if($client_status != null){

            return view('admin.crm.client_status.edit', compact('client_status'));
        }
        else{
            return redirect()->route('client_statuses.index')->with('error', 'Client Status Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:client_statuses,name',
        ]);

        $client_status = new ClientStatus();

        $client_status->name = $request->name;

        $client_status->save();

        return redirect()->route('client_statuses.index')->with('success', 'Client Status Created Successfully');

    }

    public function update(Request $request, $client_status_id)
    {
        $request->validate([
            'name' => 'required|unique:client_statuses,name,'.$client_status_id
        ]);
        
        $client_status = ClientStatus::find($client_status_id);

        if ($client_status != null) {

            $client_status->name = $request->name;

            $client_status->save();

            return redirect()->route('client_statuses.index')->with('success', 'Client Status Updated Successfully');
        }
        else{
            return redirect()->route('client_statuses.index')->with('error', 'Client Status Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $client_status = ClientStatus::find($request->client_status_id);

            if($client_status != null)
            {
                $client_status->delete();
                return response()->json(['success' => 'Client Status Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Client Status Not Found']);
            }
        }
    }

}
