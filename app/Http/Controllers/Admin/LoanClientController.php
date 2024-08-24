<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoanClient;
use Illuminate\Http\Request;
use DataTables;
use DB;
use App\Http\Controllers\Controller;

class LoanClientController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->now('view_name', 'admin.financials.loan.index');
        
        if($request->ajax()){

            $loan_clients = LoanClient::select(
                'id',
                'unique_id',
                'name',
                'address',
                'contact_no',
                'email'
            )
            ->orderBy('id', 'asc')
            ->get();

            return DataTables::of($loan_clients)
                ->addColumn('action', function ($loan_client) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="'.route('loan_clients.show', $loan_client->id).'" class="dropdown-item"><i class="ri-eye-fill eye-icon align-bottom me-2 text-muted"></i>View</a></li>
                                                <li><a href="'.route('loan_clients.edit', $loan_client->id).'" class="dropdown-item"><i class="ri-pencil-fill text-muted align-middle me-2"></i>Edit</a></li>
                                            </ul>
                                        </div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.financials.loans.loan_client.index');
    }

    public function show(Request $request, $loan_client_id)
    {
        $request->session()->now('view_name', 'admin.financials.loan.index');

        $loan_client = LoanClient::find($loan_client_id);
        
        if($loan_client != null){
            return view('admin.financials.loans.loan_client.show', compact('loan_client'));
        }
        else{
            return redirect()->route('loan_clients.index')->with('error', 'LoanClient Not Found');
        }
    }

    public function create(Request $request)
    {   
        $request->session()->now('view_name', 'admin.financials.loan.index');

        return view('admin.financials.loans.loan_client.create');
    }

    public function edit(Request $request, $loan_client_id)
    {
        $request->session()->now('view_name', 'admin.financials.loan.index');

        $loan_client = LoanClient::find($loan_client_id);
        
        if($loan_client != null){

            return view('admin.financials.loans.loan_client.edit', compact('loan_client'));
        }
        else{
            return redirect()->route('loan_client.index')->with('error', 'LoanClient Not Found');
        }
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_no' => 'required|unique:loan_clients,contact_no',
            'email' => 'required|email|unique:loan_clients,email',
        ]);

        if(!$this->validateContactNo($request->contact_no))
        {
            return redirect()->back()->withInput()->withErrors(['contact_no' => 'Invalid contact number']);
        }

        $loan_client = new LoanClient();

        $loan_client->name = $request->name;

        $loan_client->warehouse_id = session('user_warehouse')->id;

        $loan_client->address = $request->address;

        $loan_client->contact_no = $request->contact_no;

        $loan_client->email = $request->email;

        $loan_client->save();

        $loan_client->unique_id = 'Client-' . ($loan_client->id + 1000);

        $loan_client->save();
        
        return redirect()->route('loan_clients.index')->with('success', 'Loan Client Created Successfully');

    }

    public function update(Request $request, $loan_client_id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'contact_no' => 'required|unique:loan_clients,contact_no,' . $loan_client_id,
            'email' => 'nullable|email|unique:loan_clients,email,' . $loan_client_id,
        ]);
        
        if(!$this->validateContactNo($request->contact_no))
        {
            return redirect()->back()->withInput()->withErrors(['contact_no' => 'Invalid contact number']);
        }

        $loan_client = LoanClient::find($loan_client_id);

        if($loan_client != null){

            $loan_client->name = $request->name;

            $loan_client->address = $request->address;

            $loan_client->contact_no = $request->contact_no;

            $loan_client->email = $request->email;

            $loan_client->save();

            return redirect()->route('loan_clients.index')->with('success', 'Loan Client Updated Successfully');
        }
        else{
            return redirect()->route('loan_client.index')->with('error', 'Loan Client Category Not Found');
        }
    }


    public function validateContactNo($input)
    {
        $arr = ['0','1','2','3','4','5','6','7','8','9','-','+','(',')',' '];

        $flag = true;

        for($i = 0; $i < strlen($input); $i++)
        {
            if(!in_array($input[$i], $arr))
            {
                $flag = false;

                break;
            }
        }

        return $flag;
    }
}
