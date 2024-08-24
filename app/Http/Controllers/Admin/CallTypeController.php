<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\CallType;

class CallTypeController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('call_type.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.call.call_type.index');
        
        if($request->ajax()){

            $call_types = CallType::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($call_types)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('call_type.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('call_types.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('call_type.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteCallType(' . $category->id . ')">
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

        return view('admin.call.call_type.index');
    }

    public function create(Request $request)
    {
        if(!in_array('call_type.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.call.call_type.index');

        return view('admin.call.call_type.create');
    }

    public function edit(Request $request, $call_type_id)
    {
        if(!in_array('call_type.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.call.call_type.index');

        $call_type = CallType::find($call_type_id);

        if($call_type != null){

            return view('admin.call.call_type.edit', compact('call_type'));
        }
        else{
            return redirect()->route('call_types.index')->with('error', 'Call Type Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:call_types,name',
        ]);

        $call_type = new CallType();

        $call_type->name = $request->name;

        $call_type->save();

        return redirect()->route('call_types.index')->with('success', 'Call Type Created Successfully');

    }

    public function update(Request $request, $call_type_id)
    {
        $request->validate([
            'name' => 'required|unique:call_types,name,'.$call_type_id
        ]);
        
        $call_type = CallType::find($call_type_id);

        if ($call_type != null) {

            $call_type->name = $request->name;

            $call_type->save();

            return redirect()->route('call_types.index')->with('success', 'Call Type Updated Successfully');
        }
        else{
            return redirect()->route('call_types.index')->with('error', 'Call Type Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $call_type = CallType::find($request->call_type_id);

            if($call_type != null)
            {
                $call_type->delete();
                return response()->json(['success' => 'Call Type Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Call Type Not Found']);
            }
        }
    }

}
