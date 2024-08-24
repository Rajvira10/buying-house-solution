<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\ClientSource;

class ClientSourceController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('client_source.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.client_source.index');
        
        if($request->ajax()){

            $client_sources = ClientSource::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($client_sources)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('client_source.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('client_sources.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('client_source.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteClientSource(' . $category->id . ')">
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

        return view('admin.crm.client_source.index');
    }

    public function create(Request $request)
    {
        if(!in_array('client_source.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.client_source.index');

        return view('admin.crm.client_source.create');
    }

    public function edit(Request $request, $client_source_id)
    {
        if(!in_array('client_source.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.client_source.index');

        $client_source = ClientSource::find($client_source_id);

        if($client_source != null){

            return view('admin.crm.client_source.edit', compact('client_source'));
        }
        else{
            return redirect()->route('client_sources.index')->with('error', 'Client Source Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:client_sources,name',
        ]);

        $client_source = new ClientSource();

        $client_source->name = $request->name;

        $client_source->save();

        return redirect()->route('client_sources.index')->with('success', 'Client Source Created Successfully');

    }

    public function update(Request $request, $client_source_id)
    {
        $request->validate([
            'name' => 'required|unique:client_sources,name,'.$client_source_id
        ]);
        
        $client_source = ClientSource::find($client_source_id);

        if ($client_source != null) {

            $client_source->name = $request->name;

            $client_source->save();

            return redirect()->route('client_sources.index')->with('success', 'Client Source Updated Successfully');
        }
        else{
            return redirect()->route('client_sources.index')->with('error', 'Client Source Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $client_source = ClientSource::find($request->client_source_id);

            if($client_source != null)
            {
                $client_source->delete();
                return response()->json(['success' => 'Client Source Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Client Source Not Found']);
            }
        }
    }

}
