<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\RevenueCategory;

class RevenueCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('revenue_categories.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue_categories.index');
        
        if($request->ajax()){

            $revenue_categories = RevenueCategory::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($revenue_categories)
                ->addColumn('total_revenue', function ($category) {
                    return number_format($category->revenues->sum('amount'),2);
                })
                ->addColumn('action', function ($category) {
                    if(!in_array('revenue_categories.edit', session('user_permissions')))
                    {
                        return '';
                    }
                    $edit_button = '<a href="' . route('revenue_categories.edit', $category->id) . '">
                                        <button class="btn btn-sm btn-success edit-item-btn">
                                            Edit
                                        </button>
                                    </a>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.revenues.revenue_category.index');
    }

    public function create(Request $request)
    {
        if(!in_array('revenue_categories.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue_categories.index');

        return view('admin.revenues.revenue_category.create');
    }

    public function edit(Request $request, $revenue_category_id)
    {
        if(!in_array('revenue_categories.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.revenues.revenue_categories.index');

        $revenue_category = RevenueCategory::find($revenue_category_id);

        if($revenue_category != null){

            return view('admin.revenues.revenue_category.edit', compact('revenue_category'));
        }
        else{
            return redirect()->route('revenue_categories.index')->with('error', 'Revenue Category Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:revenue_categories,name',
        ]);

        $revenue_category = new RevenueCategory();

        $revenue_category->name = $request->name;

        $revenue_category->save();

        return redirect()->route('revenue_categories.index')->with('success', 'Revenue Category Created Successfully');

    }

    public function update(Request $request, $revenue_category_id)
    {
        $request->validate([
            'name' => 'required|unique:revenue_categories,name,'.$revenue_category_id
        ]);
        
        $revenue_category = RevenueCategory::find($revenue_category_id);

        if ($revenue_category != null) {

            $revenue_category->name = $request->name;

            $revenue_category->save();

            return redirect()->route('revenue_categories.index')->with('success', 'Revenue Category Updated Successfully');
        }
        else{
            return redirect()->route('revenue_categories.index')->with('error', 'Revenue Category Not Found');
        }
    }

}
