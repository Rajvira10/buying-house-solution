<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Investment;
use App\Models\User;
use App\Models\Account;
use App\Models\Investor;
use App\Models\InvestmentReturn;
use Illuminate\Http\Request;
use App\Models\TransactionPool;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Services\TransactionPoolService;

class InvestmentController extends Controller
{
    private $transaction_pool_service;

    public function __construct(TransactionPoolService $transaction_pool_service)
    {
        $this->transaction_pool_service = $transaction_pool_service;
    }

    public function index(Request $request)
    {
        if(!in_array('investment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.investment.index');
        
        try{
            if($request->ajax()){
                
                $investments = Investment::join('investors', 'investments.investor_id', '=', 'investors.id')
                ->join('accounts', 'investments.account_id', '=', 'accounts.id')
                ->select(
                    'investments.*', 
                    'investors.name as investor_name',
                    'accounts.name as account_name' 
                    )
                ->where('investments.warehouse_id', '=', session('user_warehouse')->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
                return DataTables::of($investments)

                    ->addColumn('date', function($investment){

                        return Carbon::parse($investment->date)->format('d/m/Y');

                    })

                    ->addColumn('return_status', function($investment){

                        if($investment->return_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($investment->return_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('total_paid', function($investment){
                            
                        $total_paid = $investment->returns()->where('status', '=', 'Approved')->sum('amount');

                        return $total_paid;
    
                    })

                    ->addColumn('due', function($investment){
                            
                        $total_paid = $investment->returns()->where('status', '=', 'Approved')->sum('amount');

                        $investment_amount = floatval($investment->amount);
                        
                        $due = $investment_amount - $total_paid;

                        return $due;

                    })


                    ->addColumn('finalized_by', function($expense){
                        
                        $user = User::where('id', '=', $expense->finalized_by)
                        ->select(
                            'username'
                        )
                        ->first();

                        return $user->username . ' ' . Carbon::parse($expense->finalized_at)->format('d/m/Y h:i A');
                        
                    })
                    
                    ->addColumn('investor_name', function ($investment) {

                            return $investment->investor_name;

                    })

                    ->addColumn('account_name', function ($investment) {

                        return $investment->account_name;

                    })

                    ->addColumn('action', function ($investment) {

                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">';
                        
                        if(in_array('investment.edit', session('user_permissions')))
                        {
                            $edit_button .= '<li><a href="' . route('investments.edit', $investment->id) . '" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>';
                        }

                        $edit_button .= 
                        // '<li><a href="'. route('investments.pending_returns', $investment->id).'" class="dropdown-item edit-item-btn"><i class="ri-time-fill align-bottom me-2 text-warning"></i> Pending Returns</a></li>
                                                '<li><a href="'. route('investments.approved_returns', $investment->id).'" class="dropdown-item edit-item-btn"><i class="ri-eye-fill align-bottom me-2 text-success"></i> View Returns</a></li>
                                                <li>
                                                    <a href="'.route('investments.return', $investment->id).'" class="dropdown-item remove-item-btn">
                                                        <i class="ri-currency-fill align-bottom me-2 text-info"></i> Add Investment Return
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>';

                        return $edit_button;
                    })
                    ->rawColumns(['action', 'return_status'])
                    ->make(true);
                }

            return view('admin.financials.investments.investment.index');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function create(Request $request)
    {
        if(!in_array('investment.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.financials.investment.index');

        $investors = Investor::select(
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
        
        return view('admin.financials.investments.investment.create', compact('investors', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'investor_id' => 'required|numeric|exists:investors,id',
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

            $this->transaction_pool_service->tempStoreInvestment($request, $warehouse_id);

            DB::commit();

            return redirect()->route('investments.index')->with('success', 'Investment Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('investments.index')->with('error', $bug);
        }
    }

    public function pending(Request $request)
    {
        if(!in_array('investment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.investment.index');

        try{
            if($request->ajax()){
                
                $investments = TransactionPool::select(
                    'data'
                )
                ->where('poolable_type', '=', 'App\Models\Investment')
                ->where(function ($query) {
                    $query->where('checked_by', '=', null)
                        ->orWhere('approved_by', '=', null);
                })
                ->get();
                
                $investments = $investments->map(function($item){
                    return json_decode($item->data)->official_data;
                });

                $investments = $investments->map(function($item){

                    $investor = Investor::where('id', '=', $item->investor_id)->select(
                        'name'
                    )->first();

                    $item->investor_name = $investor->name;

                    $account = Account::where('id', '=', $item->account_id)->select(
                        'name'
                    )->first();

                    $item->account_name = $account->name;

                    return $item;
                
                });

                return DataTables::of($investments)

                    ->addColumn('date', function($investment){

                        return $investment->date;

                    })

                    ->addColumn('return_status', function($investment){

                        if($investment->return_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($investment->return_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('finalized_by', function($investment){
                        
                        $user = User::where('id', '=', $investment->finalized_by)
                        ->select(
                            'username'
                        )
                        ->first();

                        return $user->username . ' ' . Carbon::parse($investment->finalized_at)->format('d/m/Y h:i A');
                    })

                    ->rawColumns(['return_status'])
                    ->make(true);
                }

            return view('admin.financials.investments.investment.pending');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function pendingReturns(Request $request, $investment_id)
    {
        if(!in_array('investment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.investment.index');

        try{
            $investment = Investment::find($investment_id);
            
            if($request->ajax()){

                $returns = InvestmentReturn::where('investment_id', '=', $investment->id)
                ->where('status', '=', 'Pending')
                ->get();

                return DataTables::of($returns)
                
                ->addColumn('date', function($return){

                    return Carbon::parse($return->date)->format('d/m/Y');

                })

                ->addColumn('account', function($return){

                    $account = Account::find($return->account_id);

                    return $account->name;

                })

                ->addColumn('finalized_by', function($return){
                    
                    $user = User::where('id', '=', $return->finalized_by)
                    ->select(
                        'username'
                    )
                    ->first();

                    return $user->username . ' ' . Carbon::parse($return->finalized_at)->format('d/m/Y h:i A');
                })
                ->make(true);
                   
                    
            }
            return view('admin.financials.investments.investment.pending_returns', compact('investment'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function approvedReturns(Request $request, $investment_id)
    {
        if(!in_array('investment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.financials.investment.index');

        try{
            $investment = Investment::find($investment_id);

            $returns = $investment->returns()->where('status', '=', 'Approved')->get();

            $total_paid = $returns->sum('amount');

            $investment_amount = floatval($investment->amount);
            
            $due = $investment_amount - $total_paid;
            
            if($request->ajax()){

                return DataTables::of($returns)
                
                ->addColumn('date', function($return){

                    return Carbon::parse($return->date)->format('d/m/Y');

                })

                ->addColumn('account', function($return){

                    $account = Account::find($return->account_id);

                    return $account->name;

                })

                ->addColumn('finalized_by', function($return){
                    
                    $user = User::where('id', '=', $return->finalized_by)
                    ->select(
                        'username'
                    )
                    ->first();

                    return $user->username . ' ' . Carbon::parse($return->finalized_at)->format('d/m/Y h:i A');
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
            return view('admin.financials.investments.investment.approved_returns', compact('investment', 'due'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function return(Request $request, $investment_id)
    {
        if(!in_array('investment.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.investment.index');

        $investment = Investment::find($investment_id);

        if($investment != null){

            $total_paid = $investment->returns()->where('status', '=', 'Approved')->sum('amount');

            $investment_amount = floatval($investment->amount);
            
            $due = $investment_amount - $total_paid;

            $accounts = Account::whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account');
                $query->where('name', '!=', 'Client Account');
            })->get();


            return view('admin.financials.investments.investment.return', compact('investment', 'accounts', 'due'));
        }
        else{
            return redirect()->route('investments.index')->with('error', 'Investment Not Found');
        }
    }

    public function storeReturn(Request $request, $investment_id)
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

            $investment = Investment::find($investment_id);

            $total_paid = $investment->returns()->where('status', '=', 'Approved')->sum('amount');

            $investment_amount = floatval($investment->amount);
            
            $due = $investment_amount - $total_paid;

            if($request->amount > $due)
            {
                 return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be more than ' . $due]);
            }
            else if($request->amount < 1)
            {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than 1']);
            }

            $investment_return = new InvestmentReturn();

            $investment_return->investment_id = $investment_id;

            $investment_return->amount = $request->amount;
            
            $investment_return->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $investment_return->account_id = $request->account_id;

            $investment_return->note = $request->note;

            $investment_return->status = 'Pending';

            $investment_return->finalized_by = auth()->user()->id;

            $investment_return->finalized_at = Carbon::now()->toDateTimeString();

            $investment_return->save();

            $investment_return->return_no = 'Ret-'. ($investment_return->id+1000);

            $investment_return->save();
            
            $this->transaction_pool_service->tempStoreInvestmentReturn($investment_return);

            DB::commit();

            return redirect()->route('investments.index')->with('success', 'Return Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('investments.index')->with('error', $bug);
        }
    }

}
