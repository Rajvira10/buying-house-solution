<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\ProductType;

class ProductTypeController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('product_type.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.query.product_type.index');
        
        if($request->ajax()){

            $product_types = ProductType::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($product_types)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('product_type.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('product_types.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('product_type.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteProductType(' . $category->id . ')">
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

        return view('admin.query.product_type.index');
    }

    public function create(Request $request)
    {
        if(!in_array('product_type.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.query.product_type.index');

        return view('admin.query.product_type.create');
    }

    public function edit(Request $request, $product_type_id)
    {
        if(!in_array('product_type.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.product_type.index');

        $product_type = ProductType::find($product_type_id);

        if($product_type != null){

            return view('admin.query.product_type.edit', compact('product_type'));
        }
        else{
            return redirect()->route('product_types.index')->with('error', 'Product Type Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:product_types,name',
        ]);

        $product_type = new ProductType();

        $product_type->name = $request->name;

        $product_type->save();

        return redirect()->route('product_types.index')->with('success', 'Product Type Created Successfully');

    }

    public function update(Request $request, $product_type_id)
    {
        $request->validate([
            'name' => 'required|unique:product_types,name,'.$product_type_id
        ]);
        
        $product_type = ProductType::find($product_type_id);

        if ($product_type != null) {

            $product_type->name = $request->name;

            $product_type->save();

            return redirect()->route('product_types.index')->with('success', 'Product Type Updated Successfully');
        }
        else{
            return redirect()->route('product_types.index')->with('error', 'Product Type Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $product_type = ProductType::find($request->product_type_id);

            if($product_type != null)
            {
                $product_type->delete();
                return response()->json(['success' => 'Product Type Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Product Type Not Found']);
            }
        }
    }

}
