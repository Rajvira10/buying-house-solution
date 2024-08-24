<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Models\Account;
use App\Models\LoanClient;
use App\Models\LoanPayback;
use Illuminate\Http\Request;
use App\Models\TransactionPool;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Services\TransactionPoolService;

class LoanController extends Controller
{
    private $transaction_pool_service;

    public function __construct(TransactionPoolService $transaction_pool_service)
    {
        $this->transaction_pool_service = $transaction_pool_service;
    }

    public function index(Request $request)
    {
        if(!in_array('loan.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.loan.index');
        
        try{
            if($request->ajax()){
                
                $loans = Loan::join('loan_clients', 'loans.loan_client_id', '=', 'loan_clients.id')
                ->join('accounts', 'loans.account_id', '=', 'accounts.id')
                ->select(
                    'loans.*', 
                    'loan_clients.name as client_name',
                    'accounts.name as account_name' 
                    )
                ->where('loans.warehouse_id', '=', session('user_warehouse')->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
                return DataTables::of($loans)

                    ->addColumn('date', function($loan){

                        return Carbon::parse($loan->date)->format('d/m/Y');

                    })

                    ->addColumn('payback_status', function($loan){

                        if($loan->payback_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($loan->payback_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('total_paid', function($loan){
                            
                        $total_paid = $loan->paybacks()->where('status', '=', 'Approved')->sum('amount');

                        return $total_paid;
    
                    })

                    ->addColumn('due', function($loan){
                            
                        $total_paid = $loan->paybacks()->where('status', '=', 'Approved')->sum('amount');

                        $loan_amount = floatval($loan->amount);
                        
                        $due = $loan_amount - $total_paid;

                        return $due;

                    })


                    ->addColumn('finalized_by', function($expense){
                        
                        $user = User::where('id', '=', $expense->finalized_by)
                        ->select(
                            'first_name'
                        )
                        ->first();

                        return $user->first_name . ' ' . Carbon::parse($expense->finalized_at)->format('d/m/Y h:i A');
                        
                    })
                    
                    ->addColumn('client_name', function ($loan) {

                            return $loan->client_name;

                    })

                    ->addColumn('account_name', function ($loan) {

                        return $loan->account_name;

                    })

                    ->addColumn('action', function ($loan) {

                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">';
                                
                        if(in_array('loan.edit', session('user_permissions')))
                        {
                            $edit_button .= '<li><a href="'. route('loans.edit', $loan->id).'" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>';
                        }

                        $edit_button .= 
                        // '<li><a href="'. route('loans.pending_paybacks', $loan->id).'" class="dropdown-item edit-item-btn"><i class="ri-time-fill align-bottom me-2 text-warning"></i> Pending Paybacks</a></li>
                                        '<li><a href="'. route('loans.approved_paybacks', $loan->id).'" class="dropdown-item edit-item-btn"><i class="ri-eye-fill align-bottom me-2 text-success"></i> View Paybacks</a></li>
                                        <li>
                                            <a href="'.route('loans.payback', $loan->id).'" class="dropdown-item remove-item-btn">
                                                <i class="ri-currency-fill align-bottom me-2 text-info"></i> Add Loan Payback
                                            </a>
                                        </li>
                                    </ul>
                                </div>';

                        return $edit_button;
                    })
                    ->rawColumns(['action', 'payback_status'])
                    ->make(true);
                }

            return view('admin.financials.loans.loan.index');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function create(Request $request)
    {
        if(!in_array('loan.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.loan.index');

        $loan_clients = LoanClient::select(
            'id',
            'name'
        )->get();

        $accounts = Account::with('account_category')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account')
                    ->where('name', '!=', 'Client Account');
            })
            ->get();
        
        return view('admin.financials.loans.loan.create', compact('loan_clients', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'loan_client_id' => 'required|numeric|exists:loan_clients,id',
                'amount' => 'required|numeric',
                'type' => 'required',
                'date' => 'required',
                'account_id' => 'required|numeric|exists:accounts,id',
                'note' => 'nullable'
            ]
        );

        DB::beginTransaction();

        try {

            $warehouse_id = session('user_warehouse')->id;
            
            $this->transaction_pool_service->tempStoreLoan($request, $warehouse_id);

            DB::commit();

            return redirect()->route('loans.index')->with('success', 'Loan Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('loans.index')->with('error', $bug);
        }
    }

    public function pending(Request $request)
    {
        if(!in_array('loan.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.loan.index');

        try{
            if($request->ajax()){
                
                $loans = TransactionPool::select(
                    'data'
                )
                ->where('poolable_type', '=', 'App\Models\Loan')
                ->where(function ($query) {
                    $query->where('checked_by', '=', null)
                        ->orWhere('approved_by', '=', null);
                })
                ->orderBy('created_at', 'desc')
                ->get();
                
                $loans = $loans->map(function($item){
                    return json_decode($item->data)->official_data;
                });

                $loans = $loans->map(function($item){

                    $loan_client = LoanClient::where('id', '=', $item->loan_client_id)->select(
                        'name'
                    )->first();

                    $item->client_name = $loan_client->name;

                    $account = Account::where('id', '=', $item->account_id)->select(
                        'name'
                    )->first();

                    $item->account_name = $account->name;

                    return $item;
                
                });

                return DataTables::of($loans)

                    ->addColumn('date', function($loan){

                        return $loan->date;

                    })

                    ->addColumn('payback_status', function($loan){

                        if($loan->payback_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($loan->payback_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('finalized_by', function($loan){
                        
                        $user = User::where('id', '=', $loan->finalized_by)
                        ->select(
                            'first_name'
                        )
                        ->first();

                        return $user->first_name . ' ' . Carbon::parse($loan->finalized_at)->format('d/m/Y h:i A');
                    })

                    ->rawColumns(['payback_status'])
                    ->make(true);
                }

            return view('admin.financials.loans.loan.pending');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function pendingPaybacks(Request $request, $loan_id)
    {
        if(!in_array('loan.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.loan.index');

        try{
            $loan = Loan::find($loan_id);
            
            if($request->ajax()){

                $paybacks = LoanPayback::where('loan_id', '=', $loan->id)
                ->where('status', '=', 'Pending')
                ->get();

                return DataTables::of($paybacks)
                
                ->addColumn('date', function($payback){

                    return Carbon::parse($payback->date)->format('d/m/Y');

                })

                ->addColumn('account', function($payback){

                    $account = Account::find($payback->account_id);

                    return $account->name;

                })

                ->addColumn('finalized_by', function($payback){
                    
                    $user = User::where('id', '=', $payback->finalized_by)
                    ->select(
                        'first_name'
                    )
                    ->first();

                    return $user->first_name . ' ' . Carbon::parse($payback->finalized_at)->format('d/m/Y h:i A');
                })
                ->make(true);
                   
                    
            }
            return view('admin.financials.loans.loan.pending_paybacks', compact('loan'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function approvedPaybacks(Request $request, $loan_id)
    {
        if(!in_array('loan.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.loan.index');

        try{
            $loan = Loan::find($loan_id);

            $paybacks = $loan->paybacks()->where('status', '=', 'Approved')->get();

            $total_paid = $paybacks->sum('amount');

            $loan_amount = floatval($loan->amount);
            
            $due = $loan_amount - $total_paid;
            
            if($request->ajax()){

                return DataTables::of($paybacks)
                
                ->addColumn('date', function($payback){

                    return Carbon::parse($payback->date)->format('d/m/Y');

                })

                ->addColumn('account', function($payback){

                    $account = Account::find($payback->account_id);

                    return $account->name;

                })

                ->addColumn('finalized_by', function($payback){
                    
                    $user = User::where('id', '=', $payback->finalized_by)
                    ->select(
                        'first_name'
                    )
                    ->first();

                    return $user->first_name . ' ' . Carbon::parse($payback->finalized_at)->format('d/m/Y h:i A');
                    })
                
                ->addColumn('action', function ($expense) {

                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="#" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        </ul>
                                    </div>';
                                    
                    return $edit_button;
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
            return view('admin.financials.loans.loan.approved_paybacks', compact('loan', 'due'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function payback(Request $request, $loan_id)
    {
        if(!in_array('loan.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.financials.loan.index');

        $loan = Loan::find($loan_id);

        if($loan != null){

            $total_paid = $loan->paybacks()->where('status', '=', 'Approved')->sum('amount');

            $loan_amount = floatval($loan->amount);
            
            $due = $loan_amount - $total_paid;

            $accounts = Account::select(
                'id',
                'name'
            )->get();

            return view('admin.financials.loans.loan.payback', compact('loan', 'accounts', 'due'));
        }
        else{
            return redirect()->route('loans.index')->with('error', 'Loan Not Found');
        }
    }

    public function storePayback(Request $request, $loan_id)
    {
        
        $request->validate(
            [
                'account_id' => 'required|numeric',
                'amount' => 'required|numeric',
                'date' => 'required',
                'note' => 'nullable'
            ]
        );

        DB::beginTransaction();

        try {

            $loan = Loan::find($loan_id);

            $total_paid = $loan->paybacks()->where('status', '=', 'Approved')->sum('amount');

            $loan_amount = floatval($loan->amount);
            
            $due = $loan_amount - $total_paid;

            if($request->amount > $due)
            {
                 return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be more than ' . $due]);
            }
            else if($request->amount < 1)
            {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than 1']);
            }

            $loan_payback = new LoanPayback();

            $loan_payback->loan_id = $loan_id;

            $loan_payback->amount = $request->amount;
            
            $loan_payback->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $loan_payback->account_id = $request->account_id;

            $loan_payback->note = $request->note;

            $loan_payback->status = 'Pending';

            $loan_payback->finalized_by = auth()->user()->id;

            $loan_payback->finalized_at = Carbon::now()->toDateTimeString();

            $loan_payback->save();

            $loan_payback->payback_no = 'Payb-'. ($loan_payback->id+1000);

            $loan_payback->save();
            
            $this->transaction_pool_service->tempStoreLoanPayback($loan_payback);

            DB::commit();

            return redirect()->route('loans.index')->with('success', 'Payback Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('loans.index')->with('error', $bug);
        }
    }

}
