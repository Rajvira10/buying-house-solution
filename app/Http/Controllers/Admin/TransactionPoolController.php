<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TransactionPool;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TransactionPoolService;
use App\Services\AccountStatementService;

class TransactionPoolController extends Controller
{
    private $transaction_pool_service, $account_statement_service;

    public function __construct(TransactionPoolService $transaction_pool_service, AccountStatementService $account_statement_service)
    {
        $this->transaction_pool_service = $transaction_pool_service;

        $this->account_statement_service = $account_statement_service;
    }
    
    public function index(Request $request)
    {
        if(!in_array('transaction_pool.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.transaction-pool.index');
        
        try{
            if($request->ajax()){
                
                $transactions = TransactionPool::select(
                    'id',
                    'poolable_type',
                    'data',
                    'action_type',
                    'checked_by',
                    'checked_at',
                    'approved_by',    
                    'approved_at',
                    'created_at'    
                )
                ->latest()
                ->get();

                return DataTables::of($transactions)

                    ->addColumn('date', function($transaction){

                        return Carbon::parse($transaction->created_at)->format('d/m/Y');

                    })

                    ->addColumn('data', function($transaction){

                        $button = '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#dataModal'.$transaction->id.'">View Details</button>';

                        $modal = '<div class="modal fade" id="dataModal'.$transaction->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">';
                        
                                                $data = json_decode($transaction->data, true)['presentation_data'];
                                                
                                                foreach ($data as $key => $value) {
                                                    $modal .= '<p><strong>'.$key.':</strong> '.$value.'</p>';
                                                }
                                                
                                                $modal .= '</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

                        return $button . $modal;
                    })

                    ->addColumn('transaction_type', function($transaction){

                        $transaction_type = explode("\\", $transaction->poolable_type);

                        return $transaction_type[count($transaction_type) - 1];
                    })

                    ->addColumn('checked_by', function($transaction){

                        if($transaction->action_type == 'Rejected')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Rejected</span>';
                        }

                        if($transaction->checked_by == null){
                            return '<span class="badge bg-warning" style="font-size: 12px">Not Checked</span>';
                        }
                        
                        $user = User::where('id', '=', $transaction->checked_by)
                        ->select(
                            'first_name',
                        )
                        ->first();
                        
                        $checked_by = '<span>'.$user->first_name . ' ' . Carbon::parse($transaction->checked_at)->format('d/m/Y h:i A').'</span>';

                        return $checked_by;
                    })

                    ->addColumn('approved_by', function($transaction){

                        if($transaction->action_type == 'Rejected')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Rejected</span>';
                        }
                        
                        if($transaction->approved_by == null){
                            return '<span class="badge bg-warning" style="font-size: 12px">Not Approved</span>';
                        }

                        $user = User::where('id', '=', $transaction->approved_by)
                        ->select(
                            'first_name',
                        )
                        ->first();

                        $approved_by = '<span>'.$user->first_name . ' ' . Carbon::parse($transaction->approved_at)->format('d/m/Y h:i A').'</span>';

                        return $approved_by;
                    })
                    
                    ->addColumn('action', function ($transaction) {
                        if($transaction->action_type == 'Rejected')
                        {
                            $edit_button = '';

                            return $edit_button;
                        }
                        if($transaction->checked_by == null){
                            $edit_button = '<div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">';

                            if(in_array('transaction_pool.check', session('user_permissions')))
                            {
                                $edit_button .= '<li>
                                                    <a href="#" class="dropdown-item check-item-btn" data-id="' . $transaction->id . '">
                                                        <i class="ri-check-fill align-bottom me-2 text-primary"></i> Check
                                                    </a>
                                                </li>';
                            }

                            if(in_array('transaction_pool.approve', session('user_permissions')))
                            {
                                $edit_button .= '<li>
                                                    <a href="'.route('transaction_pools.approve', $transaction->id).'" class="dropdown-item remove-item-btn">
                                                        <i class="ri-check-fill align-bottom me-2 text-primary"></i> Approve
                                                    </a>
                                                </li>';
                            }

                            if(in_array('transaction_pool.reject', session('user_permissions')))
                            {
                                $edit_button .= '<li>
                                                    <a href="'.route('transaction_pools.reject', $transaction->id).'" class="dropdown-item remove-item-btn">
                                                        <i class="ri-check-fill align-bottom me-2 text-danger"></i> <span class="font-weight-bold">Reject</span>
                                                    </a>
                                                </li>';
                            }

                            $edit_button .= '</ul>

                                            </div>';

                            return $edit_button;
                        }

                        if($transaction->approved_by == null){
                            $edit_button = '<div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>';

                            if(in_array('transaction_pool.check', session('user_permissions')))
                            {
                                $edit_button .= '<ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="#" class="dropdown-item check-item-btn" data-id="' . $transaction->id . '">
                                                            <i class="ri-checkbox-circle-fill align-bottom me-2 text-success"></i> Checked
                                                        </a>
                                                    </li>';
                            }

                            if(in_array('transaction_pool.approve', session('user_permissions')))
                            {
                                $edit_button .= '<li>
                                                    <a href="'.route('transaction_pools.approve', $transaction->id).'" class="dropdown-item remove-item-btn">
                                                        <i class="ri-check-fill align-bottom me-2 text-primary"></i> Approve
                                                    </a>
                                                </li>';
                            }

                            if(in_array('transaction_pool.reject', session('user_permissions')))
                            {
                                $edit_button .= '<li>
                                                    <a href="'.route('transaction_pools.reject', $transaction->id).'" class="dropdown-item remove-item-btn">
                                                        <i class="ri-check-fill align-bottom me-2 text-danger"></i> <span class="font-weight-bold">Reject</span>
                                                    </a>
                                                </li>';
                            }

                            $edit_button .= '</ul>

                                            </div>';
                            return $edit_button;
                        }

                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">';
                        
                        if(in_array('transaction_pool.check', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a href="#" class="dropdown-item check-item-btn" data-id="' . $transaction->id . '">
                                                    <i class="ri-checkbox-circle-fill align-bottom me-2 text-success"></i> Checked
                                                </a>
                                            </li>';
                        }

                        if(in_array('transaction_pool.approve', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a href="#" class="dropdown-item remove-item-btn">
                                                    <i class="ri-checkbox-circle-fill align-bottom me-2 text-success"></i> Approved
                                                </a>
                                            </li>';
                        }

                        $edit_button .= '</ul>
                                        </div>';

                        return $edit_button;

                    })

                        
                    ->rawColumns(['action', 'checked_by', 'approved_by', 'data'])
                    ->make(true);
                }

            return view('admin.transaction_pool.index');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function check(Request $request)
    {   
        try{

            $transaction_pool = TransactionPool::find($request->transaction_pool_id);

            $transaction_pool->checked_by = auth()->user()->id;
            
            $transaction_pool->checked_at = Carbon::now();
            
            $transaction_pool->save();

            return redirect()->back()->with('success', 'Transaction checked successfully');
        
        } catch (\Exception $e) {
            
            $bug = $e->getMessage();
            
            return redirect()->back()->with('error', $bug);
        }
    }

    public function approve($transaction_pool_id)
    {
        DB::beginTransaction();

        try{

            $transaction_pool = TransactionPool::find($transaction_pool_id);

            if($transaction_pool->checked_by == null){
                
                return redirect()->back()->with('error', 'Transaction not checked yet');
            
            }

            $transaction_pool->approved_by = auth()->user()->id;
            
            $transaction_pool->approved_at = Carbon::now();
            
            $transaction_pool->save();

            if($transaction_pool->poolable_type == 'App\Models\Expense')
            {
                $this->transaction_pool_service->storeExpense($transaction_pool);
            }
            else if($transaction_pool->poolable_type == 'App\Models\Revenue')
            {
                $this->transaction_pool_service->storeRevenue($transaction_pool);
            }
            else if($transaction_pool->poolable_type == 'App\Models\MoneyTransfer')
            {
                [$sender_account_statement_arr, $receiver_account_statement_arr] = $this->transaction_pool_service->storeMoneyTransfer($transaction_pool);

                $this->account_statement_service->store($sender_account_statement_arr);

                $this->account_statement_service->store($receiver_account_statement_arr);
            }
            else if($transaction_pool->poolable_type == 'App\Models\Loan')
            {
                $account_statement_arr = $this->transaction_pool_service->storeLoan($transaction_pool);

                $this->account_statement_service->store($account_statement_arr);
            }
            else if($transaction_pool->poolable_type == 'App\Models\LoanPayback')
            {
                $account_statement_arr = $this->transaction_pool_service->storeLoanPayback($transaction_pool);

                $this->account_statement_service->store($account_statement_arr);
            }
            else if($transaction_pool->poolable_type == 'App\Models\Investment')
            {
                $account_statement_arr = $this->transaction_pool_service->storeInvestment($transaction_pool);

                $this->account_statement_service->store($account_statement_arr);
            }
            else if($transaction_pool->poolable_type == 'App\Models\InvestmentReturn')
            {
                $account_statement_arr = $this->transaction_pool_service->storeInvestmentReturn($transaction_pool);

                $this->account_statement_service->store($account_statement_arr);
            }
            else if($transaction_pool->poolable_type == 'App\Models\Payment')
            {
                $payment_for = json_decode($transaction_pool->data, true)['payment_for'];

                if($payment_for == 'Expense')
                {
                    $account_statement_arr = $this->transaction_pool_service->storeExpensePayment($transaction_pool);
                }
                else if($payment_for == 'Project Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storeProjectPayment($transaction_pool);
                }
                else if($payment_for == 'Revenue')
                {
                    $account_statement_arr = $this->transaction_pool_service->storeRevenuePayment($transaction_pool);
                }
                else if($payment_for == 'Purchase Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storePurchasePayment($transaction_pool);
                }
                else if($payment_for == 'Purchase Return Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storePurchaseReturnPayment($transaction_pool);
                }
                else if($payment_for == 'Sale Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storeSalePayment($transaction_pool);
                }
                else if($payment_for == 'Sale Return Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storeSaleReturnPayment($transaction_pool);
                }
                else if($payment_for == 'Wholesale Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storeWholesalePayment($transaction_pool);
                }
                else if($payment_for == 'Wholesale Return Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storeWholesaleReturnPayment($transaction_pool);
                }
                else if($payment_for == 'Payment')
                {
                    $account_statement_arr = $this->transaction_pool_service->storePayment($transaction_pool);
                }

                $this->account_statement_service->store($account_statement_arr);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Transaction approved successfully');
        
        } catch (\Exception $e) {
            
            DB::rollback();

            $bug = $e->getMessage();
            
            return redirect()->back()->with('error', $bug);
        }
    }
    
    public function reject($transaction_pool_id)
    {
        try{

            $transaction_pool = TransactionPool::find($transaction_pool_id);

            if($transaction_pool->poolable_type == 'App\Models\Payment')
            {
                $this->transaction_pool_service->rejectPayment($transaction_pool);
            }

            return redirect()->back()->with('success', 'Transaction rejected successfully');
        
        } catch (\Exception $e) {
            
            $bug = $e->getMessage();
            
            return redirect()->back()->with('error', $bug);
        }
    }
}


