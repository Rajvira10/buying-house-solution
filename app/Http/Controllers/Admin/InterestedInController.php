<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\InterestedIn;

class InterestedInController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('interested_in.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.interested_in.index');
        
        if($request->ajax()){

            $interested_ins = InterestedIn::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($interested_ins)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('interested_in.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('interested_ins.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('interested_in.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteInterestedIn(' . $category->id . ')">
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

        return view('admin.crm.interested_in.index');
    }

    public function create(Request $request)
    {
        if(!in_array('interested_in.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.interested_in.index');

        return view('admin.crm.interested_in.create');
    }

    public function edit(Request $request, $interested_in_id)
    {
        if(!in_array('interested_in.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.interested_in.index');

        $interested_in = InterestedIn::find($interested_in_id);

        if($interested_in != null){

            return view('admin.crm.interested_in.edit', compact('interested_in'));
        }
        else{
            return redirect()->route('interested_ins.index')->with('error', 'Interested In Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:interested_ins,name',
        ]);

        $interested_in = new InterestedIn();

        $interested_in->name = $request->name;

        $interested_in->save();

        return redirect()->route('interested_ins.index')->with('success', 'Interested In Created Successfully');

    }

    public function update(Request $request, $interested_in_id)
    {
        $request->validate([
            'name' => 'required|unique:interested_ins,name,'.$interested_in_id
        ]);
        
        $interested_in = InterestedIn::find($interested_in_id);

        if ($interested_in != null) {

            $interested_in->name = $request->name;

            $interested_in->save();

            return redirect()->route('interested_ins.index')->with('success', 'Interested In Updated Successfully');
        }
        else{
            return redirect()->route('interested_ins.index')->with('error', 'Interested In Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $interested_in = InterestedIn::find($request->interested_in_id);

            if($interested_in != null)
            {
                $interested_in->delete();
                return response()->json(['success' => 'Interested In Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Interested In Not Found']);
            }
        }
    }

}
