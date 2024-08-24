<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\Trim;

class TrimController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('trim.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.query.trim.index');
        
        if($request->ajax()){

            $trims = Trim::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($trims)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('trim.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('trims.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('trim.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteTrim(' . $category->id . ')">
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

        return view('admin.query.trim.index');
    }

    public function create(Request $request)
    {
        if(!in_array('trim.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.query.trim.index');

        return view('admin.query.trim.create');
    }

    public function edit(Request $request, $trim_id)
    {
        if(!in_array('trim.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.trim.index');

        $trim = Trim::find($trim_id);

        if($trim != null){

            return view('admin.query.trim.edit', compact('trim'));
        }
        else{
            return redirect()->route('trims.index')->with('error', 'Trim Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:trims,name',
        ]);

        $trim = new Trim();

        $trim->name = $request->name;

        $trim->save();

        return redirect()->route('trims.index')->with('success', 'Trim Created Successfully');

    }

    public function update(Request $request, $trim_id)
    {
        $request->validate([
            'name' => 'required|unique:trims,name,'.$trim_id
        ]);
        
        $trim = Trim::find($trim_id);

        if ($trim != null) {

            $trim->name = $request->name;

            $trim->save();

            return redirect()->route('trims.index')->with('success', 'Trim Updated Successfully');
        }
        else{
            return redirect()->route('trims.index')->with('error', 'Trim Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $trim = Trim::find($request->trim_id);

            if($trim != null)
            {
                $trim->delete();
                return response()->json(['success' => 'Trim Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Trim Not Found']);
            }
        }
    }

}
