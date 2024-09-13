<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplierContactPerson;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('supplier.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.supplier.index');
        
        if($request->ajax()){

            $factories = Supplier::select(
                'id',
                'name',
                'phone'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($factories)
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('supplier.index', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('factories.show', $category->id).'" class
                        ="dropdown-item"><i class="ri-eye-fill me-2"></i> View</a></li>';
                    }
                    if(in_array('supplier.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('factories.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('supplier.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteSupplier(' . $category->id . ')">
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

        return view('admin.crm.supplier.index');
    }

    public function create(Request $request)
    {
        if(!in_array('supplier.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.supplier.index');

        return view('admin.crm.supplier.create');
    }

    public function edit(Request $request, $supplier_id)
    {
        if(!in_array('supplier.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.supplier.index');

        $supplier = Supplier::find($supplier_id);

        if($supplier != null){

            return view('admin.crm.supplier.edit', compact('supplier'));
        }
        else{
            return redirect()->route('factories.index')->with('error', 'Supplier Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:factories,name',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email|unique:factories,email',
        ]);

        $supplier = new Supplier();

        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;

        $supplier->save();

        return redirect()->route('factories.index')->with('success', 'Supplier Created Successfully');

    }

    public function show(Request $request, $id)
    {
        if(!in_array('supplier.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.supplier.index');

        $supplier = Supplier::with('contact_people')->find($id);

        return view('admin.crm.supplier.show', compact('supplier'));
    }

    public function update(Request $request, $supplier_id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email|unique:factories,email,'.$supplier_id
        ]);
        
        $supplier = Supplier::find($supplier_id);

        if ($supplier != null) {

            $supplier->name = $request->name;
            $supplier->address = $request->address;
            $supplier->phone = $request->phone;
            $supplier->email = $request->email;

            $supplier->save();

            return redirect()->route('factories.index')->with('success', 'Supplier Updated Successfully');
        }
        else{
            return redirect()->route('factories.index')->with('error', 'Supplier Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $supplier = Supplier::find($request->supplier_id);

            if($supplier != null)
            {
                $supplier->contact_people()->delete();
                $supplier->delete();
                return response()->json(['success' => 'Supplier Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Supplier Not Found']);
            }
        }
    }

    public function storeContactPerson(Request $request)
    {
        if($request->ajax()){
            $request->validate([
                'name' => 'required',
                'phone' => 'nullable',
                'email' => 'nullable|email',
                'designation' => 'nullable',
                'supplier_id' => 'required|exists:factories,id'
            ]);

            try {
                $contact_person = new SupplierContactPerson();

                $contact_person->name = $request->name;
                $contact_person->phone = $request->phone;
                $contact_person->email = $request->email;
                $contact_person->designation = $request->designation;
                $contact_person->supplier_id = $request->supplier_id;

                $contact_person->save();

                return response()->json(['success' => 'Contact Person Created Successfully']);
            } catch (\Throwable $th) {
                return response()->json(['error' => 'Something went wrong']);
            }
            
        }
    }

    public function updateContactPerson(Request $request)
    {
        $contactPerson = SupplierContactPerson::findOrFail($request->contact_person_id); 

        if($contactPerson != null)
        {
            $request->validate([
                'name' => 'required',
                'phone' => 'nullable',
                'email' => 'nullable|email',
                'designation' => 'nullable',
            ]);

            try {
                $contactPerson->name = $request->name;
                $contactPerson->phone = $request->phone;
                $contactPerson->email = $request->email;
                $contactPerson->designation = $request->designation;

                $contactPerson->save();

                return response()->json(['success' => 'Contact Person Updated Successfully']);
            } catch (\Throwable $th) {
                return response()->json(['error' => $th->getMessage()]);
            }
        }
        else{
            return response()->json(['error' => 'Contact Person Not Found']);
        }
    }

    public function deleteContactPerson (Request $request)
    {
        if($request->ajax())
        {
            $contactPerson = SupplierContactPerson::find($request->contact_person_id);

            if($contactPerson != null)
            {
                $contactPerson->delete();
                return response()->json(['success' => 'Contact Person Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Contact Person Not Found']);
            }
        }
    }
}

