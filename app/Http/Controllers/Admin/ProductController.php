<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('product.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.query.product.index');
        
        if($request->ajax()){

            $products = Product::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($products)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('product.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('products.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('product.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteProduct(' . $category->id . ')">
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

        return view('admin.query.product.index');
    }

    public function create(Request $request)
    {
        if(!in_array('product.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.query.product.index');

        return view('admin.query.product.create');
    }

    public function edit(Request $request, $product_id)
    {
        if(!in_array('product.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.product.index');

        $product = Product::find($product_id);

        if($product != null){

            return view('admin.query.product.edit', compact('product'));
        }
        else{
            return redirect()->route('products.index')->with('error', 'Product Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products,name',
        ]);

        $product = new Product();

        $product->name = $request->name;

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product Created Successfully');

    }

    public function update(Request $request, $product_id)
    {
        $request->validate([
            'name' => 'required|unique:products,name,'.$product_id
        ]);
        
        $product = Product::find($product_id);

        if ($product != null) {

            $product->name = $request->name;

            $product->save();

            return redirect()->route('products.index')->with('success', 'Product Updated Successfully');
        }
        else{
            return redirect()->route('products.index')->with('error', 'Product Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $product = Product::find($request->product_id);

            if($product != null)
            {
                $product->delete();
                return response()->json(['success' => 'Product Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Product Not Found']);
            }
        }
    }

}
