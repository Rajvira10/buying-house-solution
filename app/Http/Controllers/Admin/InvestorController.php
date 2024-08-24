<?php

namespace App\Http\Controllers\Admin;

use App\Models\Investor;
use Illuminate\Http\Request;
use DataTables;
use DB;
use App\Http\Controllers\Controller;

class InvestorController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->now('view_name', 'admin.financials.investment.index');
        
        if($request->ajax()){

            $investors = Investor::select(
                'id',
                'unique_id',
                'name',
                'address',
                'contact_no',
                'email'
            )
            ->orderBy('id', 'asc')
            ->get();

            return DataTables::of($investors)
                ->addColumn('action', function ($investor) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="'.route('investors.show', $investor->id).'" class="dropdown-item"><i class="ri-eye-fill eye-icon align-bottom me-2 text-muted"></i>View</a></li>
                                                <li><a href="'.route('investors.edit', $investor->id).'" class="dropdown-item"><i class="ri-pencil-fill text-muted align-middle me-2"></i>Edit</a></li>
                                            </ul>
                                        </div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.financials.investments.investor.index');
    }

    public function show(Request $request, $investor_id)
    {
        $request->session()->now('view_name', 'admin.financials.investment.index');

        $investor = Investor::find($investor_id);
        
        if($investor != null){
            return view('admin.financials.investments.investor.show', compact('investor'));
        }
        else{
            return redirect()->route('investors.index')->with('error', 'Investor Not Found');
        }
    }

    public function create(Request $request)
    {   
        $request->session()->now('view_name', 'admin.financials.investment.index');

        return view('admin.financials.investments.investor.create');
    }

    public function edit(Request $request, $investor_id)
    {
        $request->session()->now('view_name', 'admin.financials.investment.index');

        $investor = Investor::find($investor_id);
        
        if($investor != null){

            return view('admin.financials.investments.investor.edit', compact('investor'));
        }
        else{
            return redirect()->route('investor.index')->with('error', 'Investor Not Found');
        }
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_no' => 'required|unique:investors,contact_no',
            'email' => 'required|email|unique:investors,email',
        ]);

        if(!$this->validateContactNo($request->contact_no))
        {
            return redirect()->back()->withInput()->withErrors(['contact_no' => 'Invalid contact number']);
        }

        $investor = new Investor();

        $investor->name = $request->name;

        $investor->warehouse_id = session('user_warehouse')->id;

        $investor->address = $request->address;

        $investor->contact_no = $request->contact_no;

        $investor->email = $request->email;

        $investor->save();

        $investor->unique_id = 'Investor-' . ($investor->id + 1000);

        $investor->save();
        
        return redirect()->route('investors.index')->with('success', 'Investor Created Successfully');

    }

    public function update(Request $request, $investor_id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'contact_no' => 'required|unique:investors,contact_no,' . $investor_id,
            'email' => 'required|email|unique:investors,email,' . $investor_id,
        ]);
        
        if(!$this->validateContactNo($request->contact_no))
        {
            return redirect()->back()->withInput()->withErrors(['contact_no' => 'Invalid contact number']);
        }

        $investor = Investor::find($investor_id);

        if($investor != null){

            $investor->name = $request->name;

            $investor->address = $request->address;

            $investor->contact_no = $request->contact_no;

            $investor->email = $request->email;

            $investor->save();

            return redirect()->route('investors.index')->with('success', 'Investor Updated Successfully');
        }
        else{
            return redirect()->route('investor.index')->with('error', 'Investor Category Not Found');
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
