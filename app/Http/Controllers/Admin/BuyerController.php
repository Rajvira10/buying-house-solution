<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\User;
use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Models\BuyerContactPerson;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\GenerateRandomPassword;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('buyer.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.buyer.index');
        
        if($request->ajax()){

            $buyers = Buyer::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($buyers)
                ->addColumn('name', function ($category) {
                    return $category->user->username;
                }) 
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';

                    $edit_button .= '<li><a href="'.route('buyers.show', $category->id).'" class
                    ="dropdown-item"><i class="ri-eye-fill me-2"></i> View</a></li>';

                    if(in_array('buyer.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('buyers.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('buyer.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteBuyer(' . $category->id . ')">
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

        return view('admin.crm.buyer.index');
    }

    public function create(Request $request)
    {
        if(!in_array('buyer.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.crm.buyer.index');

        return view('admin.crm.buyer.create');
    }

    public function edit(Request $request, $buyer_id)
    {
        if(!in_array('buyer.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.crm.buyer.index');

        $buyer = Buyer::find($buyer_id);

        if($buyer != null){

            return view('admin.crm.buyer.edit', compact('buyer'));
        }
        else{
            return redirect()->route('buyers.index')->with('error', 'Buyer Not Found');
        }
    }

    public function show(Request $request, $buyer_id)
    {
        if(!in_array('buyer.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.crm.buyer.index');
    
        $buyer = Buyer::find($buyer_id);

        if($buyer != null){
            return view('admin.crm.buyer.show', compact('buyer'));
        }
        else{
            return redirect()->route('buyers.index')->with('error', 'Buyer Not Found');
        }

    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

            DB::beginTransaction();

            try {
                $user = new User();

                $user->username = $request->username;
                
                $user->warehouse_id = session('user_warehouse')->id;

                $user->email = $request->email;
                
                $password = 'buyer123';

                $user->password = Hash::make($password);

                $user->type = 'buyer';
                
                $user->save();

                $buyer = new Buyer();

                $buyer->user_id = $user->id;

                $buyer->phone = $request->phone;

                $buyer->address = $request->address;

                $buyer->save();

                DB::commit();

                return redirect()->route('buyers.index')->with(['success' => 'Buyer Created Successfully', 'password' => $password, 'email' => $request->email]);

            } catch (\Throwable $th) {
                DB::rollBack();
                dd($th->getMessage());
                return redirect()->back()->with('error', $th->getMessage());
            }
    }

    public function update(Request $request, $buyer_id)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . Buyer::find($buyer_id)->user_id,
            'password' => 'nullable|min:6|confirmed',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $buyer = Buyer::find($buyer_id);

            if($buyer != null){

                $user = User::find($buyer->user_id);

                $user->username = $request->username;
                
                $user->email = $request->email;

                if($request->password != null)
                {
                    $user->password = Hash::make($request->password);
                }
                
                $user->save();

                $buyer->phone = $request->phone;

                $buyer->address = $request->address;

                $buyer->save();

                DB::commit();

                return redirect()->route('buyers.index')->with('success', 'Buyer Updated Successfully');
            }
            else{
                return redirect()->route('buyers.index')->with('error', 'Buyer Not Found');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $buyer = Buyer::find($request->buyer_id);

            if($buyer != null)
            {
                $buyer->user->roles()->detach();
                $buyer->delete();
                $buyer->user->delete();
                return response()->json(['success' => 'Buyer Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Buyer Not Found']);
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
                'buyer_id' => 'required|exists:buyers,id'
            ]);

            try {
                $contact_person = new BuyerContactPerson();

                $contact_person->name = $request->name;
                $contact_person->phone = $request->phone;
                $contact_person->email = $request->email;
                $contact_person->designation = $request->designation;
                $contact_person->buyer_id = $request->buyer_id;

                $contact_person->save();

                return response()->json(['success' => 'Contact Person Created Successfully']);
            } catch (\Throwable $th) {
                return response()->json(['error' => 'Something went wrong']);
            }
            
        }
    }

    public function updateContactPerson(Request $request)
    {
        $contactPerson = BuyerContactPerson::findOrFail($request->contact_person_id); 

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
            $contactPerson = BuyerContactPerson::find($request->contact_person_id);

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
