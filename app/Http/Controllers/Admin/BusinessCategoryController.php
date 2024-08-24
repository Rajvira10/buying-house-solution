<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;

class BusinessCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('business_category.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.business_category.index');
        
        if($request->ajax()){

            $business_categories = BusinessCategory::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($business_categories)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('business_category.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('business_categories.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('business_category.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteBusinessCategory(' . $category->id . ')">
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

        return view('admin.crm.business_category.index');
    }

    public function create(Request $request)
    {
        if(!in_array('business_category.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.business_category.index');

        return view('admin.crm.business_category.create');
    }

    public function edit(Request $request, $business_category_id)
    {
        if(!in_array('business_category.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.business_category.index');

        $business_category = BusinessCategory::find($business_category_id);

        if($business_category != null){

            return view('admin.crm.business_category.edit', compact('business_category'));
        }
        else{
            return redirect()->route('business_categories.index')->with('error', 'Business Category Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:business_categories,name',
        ]);

        $business_category = new BusinessCategory();

        $business_category->name = $request->name;

        $business_category->save();

        return redirect()->route('business_categories.index')->with('success', 'Business Category Created Successfully');

    }

    public function update(Request $request, $business_category_id)
    {
        $request->validate([
            'name' => 'required|unique:business_categories,name,'.$business_category_id
        ]);
        
        $business_category = BusinessCategory::find($business_category_id);

        if ($business_category != null) {

            $business_category->name = $request->name;

            $business_category->save();

            return redirect()->route('business_categories.index')->with('success', 'Business Category Updated Successfully');
        }
        else{
            return redirect()->route('business_categories.index')->with('error', 'Business Category Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $business_category = BusinessCategory::find($request->business_category_id);

            if($business_category != null)
            {
                $business_category->delete();
                return response()->json(['success' => 'Business Category Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Business Category Not Found']);
            }
        }
    }

}
