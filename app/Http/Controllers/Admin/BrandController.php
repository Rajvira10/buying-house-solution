<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Brand;
use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Models\BrandContactPerson;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('brand.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.brand.index');
        
        if($request->ajax()){

            $brands = Brand::select(
                'id',
                'name',
                'phone'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($brands)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('brand.index', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('brands.show', $category->id).'" class
                        ="dropdown-item"><i class="ri-eye-fill me-2"></i> View</a></li>';
                    }
                    if(in_array('brand.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('brands.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('brand.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteBrand(' . $category->id . ')">
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

        return view('admin.crm.brand.index');
    }

    public function create(Request $request)
    {
        if(!in_array('brand.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.brand.index');

        $buyers = Buyer::with('user')->get();

        return view('admin.crm.brand.create', compact('buyers'));
    }

    public function edit(Request $request, $brand_id)
    {
        if(!in_array('brand.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.brand.index');

        $brand = Brand::find($brand_id);

        if($brand != null){

           $buyers = Buyer::with('user')->get();

            return view('admin.crm.brand.edit', compact('brand', 'buyers'));
        }
        else{
            return redirect()->route('brands.index')->with('error', 'Brand Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email|unique:brands,email',
            'buyer_id' => 'nullable|exists:buyers,id'
        ]);

        $brand = new Brand();

        $brand->name = $request->name;
        $brand->address = $request->address;
        $brand->phone = $request->phone;
        $brand->email = $request->email;
        $brand->buyer_id = $request->buyer_id;

        $brand->save();

        if($request->modal == 'true'){
            return back()->with(['success' => 'Brand Created Successfully']);
        }

        return redirect()->route('brands.index')->with(['success' => 'Brand Created Successfully']);


    }

    public function show(Request $request, $id)
    {
        if(!in_array('brand.show', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.brand.index');

        $brand = Brand::with('buyer')->find($id);

        return view('admin.crm.brand.show', compact('brand'));
    }

    public function update(Request $request, $brand_id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email|unique:brands,email,'.$brand_id,
            'buyer_id' => 'nullable|exists:buyers,id'
        ]);
        
        $brand = Brand::find($brand_id);

        if ($brand != null) {

            $brand->name = $request->name;
            $brand->address = $request->address;
            $brand->phone = $request->phone;
            $brand->email = $request->email;
            $brand->buyer_id = $request->buyer_id;

            $brand->save();

            return redirect()->route('brands.index')->with('success', 'Brand Updated Successfully');
        }
        else{
            return redirect()->route('brands.index')->with('error', 'Brand Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $brand = Brand::find($request->brand_id);

            if($brand != null)
            {
                $brand->delete();
                return response()->json(['success' => 'Brand Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Brand Not Found']);
            }
        }
    }
}

