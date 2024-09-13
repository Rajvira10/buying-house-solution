<?php

namespace App\Http\Controllers\Admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\MoneyTransfer;
use App\Models\TransactionPool;
use App\Models\AccountStatement;
use App\Http\Controllers\Controller;
use App\Services\TransactionPoolService;


class MoneyTransferController extends Controller
{
    private $transaction_pool_service;

    public function __construct(TransactionPoolService $transaction_pool_service)
    {
        $this->transaction_pool_service = $transaction_pool_service;
    }

    public function index(Request $request)
    {
        if(!in_array('money_transfer.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.money_transfer.index');

        $money_transfer_id = $request->id;

        try{
            if($request->ajax()){

                $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
                $end_date = Carbon::now()->endOfMonth()->endOfDay();

                if ($request->has('start_date') && $request->has('end_date')) {
                    $start_date = $request->start_date;

                    if ($request->end_date ) {
                        $end_date = Carbon::parse($request->end_date)->endOfDay();
                    }
                }

                if($request->id != null)
                {
                    $start_date = '1970-01-01';
                    $end_date = Carbon::now()->endOfMonth()->endOfDay();
                }

                if(in_array('see_everything', session('user_permissions')))
                {
                    $money_transfers = MoneyTransfer::join('accounts as sender_accounts', 'money_transfers.sender_account_id', '=', 'sender_accounts.id')
                    ->join('accounts as receiver_accounts', 'money_transfers.receiver_account_id', '=', 'receiver_accounts.id')
                    ->select(
                    'money_transfers.*',
                    'sender_accounts.name as sender_account_name',
                    'receiver_accounts.name as receiver_account_name'
                    )
                    ->where('money_transfers.warehouse_id', '=', session('user_warehouse')->id)
                    ->whereBetween(DB::raw('DATE(date)'), [$start_date, $end_date])
                    ->orderBy('money_transfers.created_at', 'desc')
                    ->get();
                }
                else
                {
                    $money_transfers = MoneyTransfer::join('accounts as sender_accounts', 'money_transfers.sender_account_id', '=', 'sender_accounts.id')
                    ->join('accounts as receiver_accounts', 'money_transfers.receiver_account_id', '=', 'receiver_accounts.id')
                    ->select(
                    'money_transfers.*',
                    'sender_accounts.name as sender_account_name',
                    'receiver_accounts.name as receiver_account_name'
                    )
                    ->where('money_transfers.finalized_by', '=', session('user')->id)
                    ->where('money_transfers.warehouse_id', '=', session('user_warehouse')->id)
                    ->whereBetween(DB::raw('DATE(date)'), [$start_date, $end_date])
                    ->orderBy('money_transfers.created_at', 'desc')
                    ->get();
                }

                if($request->id != null)
                {
                    $money_transfers = $money_transfers->where('id', '=', $money_transfer_id);
                }

                return DataTables::of($money_transfers)
                    ->addColumn('date', function($transfer){
                        return Carbon::parse($transfer->date)->format('d/m/Y');
                    })

                    ->addColumn('sender_account_name', function ($transfer) {
                            return $transfer->sender_account_name;
                        })

                    ->addColumn('receiver_account_name', function ($transfer) {
                            return $transfer->receiver_account_name;
                        })
                    
                    ->addColumn('finalized_by', function($transfer){
                        
                        $user = User::where('id', '=', $transfer->finalized_by)
                        ->select(
                            'username'
                        )
                        ->first();

                        return $user->username . ' ' . Carbon::parse($transfer->finalized_at)->format('d/m/Y h:i A');
                        
                    })
                    
                    ->addColumn('action', function ($transfer) {

                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">';
                        
                        if(in_array('money_transfer.edit', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a href="'.route('money_transfers.edit', $transfer->id).'" class="dropdown-item edit-item-btn">
                                                    <i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit
                                                </a>
                                            </li>';
                        }

                        if(in_array('money_transfer.delete', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteMoneyTransfer(' . $transfer->id . ')">
                                                    <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                                </button>
                                            </li>';
                        }

                        $edit_button .= '</ul>
                                        </div>';

                        return $edit_button;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
                }

            return view('admin.financials.money_transfer.index', compact('money_transfer_id'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function create(Request $request)
    {
        if(!in_array('money_transfer.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.money_transfer.index');

        try {
            $accounts = Account::join('account_categories as ac', 'account_category_id', '=', 'ac.id')
                ->where('ac.name', '!=', 'Client Account')
                ->where('ac.name', '!=', 'Supplier Account')
                ->where('warehouse_id', '=', session('user_warehouse')->id)
                ->select(
                    'accounts.*',
                    'ac.name as category_name'
                )
                ->orderBy('accounts.name') 
                ->get();

            return view('admin.financials.money_transfer.create', compact('accounts'));
        } catch (\Throwable $th) {
            return redirect()->route('money_transfers.index')->with('error', 'Something Went Wrong');
        }
    }

    public function edit(Request $request, $money_transfer_id)
    {
        if(!in_array('money_transfer.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.financials.money_transfer.index');

        try {
            $accounts = Account::join('account_categories as ac', 'account_category_id', '=', 'ac.id')
                ->where('ac.name', '!=', 'Client Account')
                ->where('ac.name', '!=', 'Supplier Account')
                ->where('warehouse_id', '=', session('user_warehouse')->id)
                ->select(
                    'accounts.*',
                    'ac.name as category_name'
                )
                ->orderBy('accounts.name') 
                ->get();

            $money_transfer = MoneyTransfer::find($money_transfer_id);

            return view('admin.financials.money_transfer.edit', compact('accounts', 'money_transfer'));
            
        } catch (\Throwable $th) {
            return redirect()->route('money_transfers.index')->with('error', 'Something Went Wrong');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_account_id' => 'required',
            'receiver_account_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $warehouse_id = session('user_warehouse')->id;

            if($request->sender_account_id == $request->receiver_account_id)
            {
                return redirect()->route('money_transfers.index')->with('error', 'Sender and Receiver Account can not be same.');
            }
            
            $this->transaction_pool_service->tempStoreMoneyTransfer($request, $warehouse_id);

            DB::commit();
            
            return redirect()->route('money_transfers.index')->with('success', 'Money Transfer created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('money_transfers.index')->with('error', $th->getMessage());
        }
        
    }

    public function update(Request $request, $money_transfer_id)
    {
        $request->validate([
            'sender_account_id' => 'required',
            'receiver_account_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        
        DB::beginTransaction();

        try {
            
            $money_transfer = MoneyTransfer::find($money_transfer_id);

            $old_amount = $money_transfer->amount;

            $old_sender_account_id = $money_transfer->sender_account_id;

            $old_receiver_account_id = $money_transfer->receiver_account_id;

            if($request->sender_account_id == $request->receiver_account_id)
            {
                return redirect()->route('money_transfers.index')->with('error', 'Sender and Receiver Account can not be same.');
            }

            $money_transfer->sender_account_id = $request->sender_account_id;

            $money_transfer->receiver_account_id = $request->receiver_account_id;

            $money_transfer->amount = $request->amount;

            $money_transfer->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $money_transfer->note = $request->note;

            $money_transfer->save();

            $account_statement = AccountStatement::where('type', '=', 'Money Transfer')
            ->where('reference_id', '=', $money_transfer_id)
            ->where('account_id', '=', $old_sender_account_id)
            ->first();

            $account_statement->account_id = $request->sender_account_id;

            $account_statement->amount = (-1)* $request->amount;

            $account_statement->save();

            $account_statement = AccountStatement::where('type', '=', 'Money Transfer')
            ->where('reference_id', '=', $money_transfer_id)
            ->where('account_id', '=', $old_receiver_account_id)
            ->first();

            $account_statement->account_id = $request->receiver_account_id;

            $account_statement->amount = $request->amount;

            $account_statement->save();

            DB::commit();

            return redirect()->route('money_transfers.index')->with('success', 'Money Transfer updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('money_transfers.index')->with('error', $th->getMessage());
        }
    
    }

    public function pending(Request $request)
    {
        if(!in_array('money_transfer.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.financials.money_transfer.index');

        try{
            if($request->ajax()){
                
                $money_transfers = TransactionPool::select(
                    'id',
                    'data'
                )
                ->where('poolable_type', '=', 'App\Models\MoneyTransfer')
                ->where(function ($query) {
                    $query->where('checked_by', '=', null)
                        ->orWhere('approved_by', '=', null);
                })
                ->get();
                
                $money_transfers = $money_transfers->map(function($item){
                    $decodedData = json_decode($item->data);
                    return [
                        'id' => $item->id,
                        'official_data' => $decodedData->official_data,
                    ];
                });

                return DataTables::of($money_transfers)

                    ->addColumn('date', function($transfer){

                        return $transfer['official_data']->date;

                    })

                    ->addColumn('sender_account_name', function ($transfer) {
                        $sender_account_name = Account::find($transfer['official_data']->sender_account_id)->name;
                        return $sender_account_name;        
                    })

                    ->addColumn('receiver_account_name', function ($transfer) {
                        $receiver_account_name = Account::find($transfer['official_data']->receiver_account_id)->name;
                        return $receiver_account_name;        
                    })

                    ->addColumn('amount', function($transfer){
                        return $transfer['official_data']->amount;
                    })

                    ->addColumn('note', function($transfer){
                        return $transfer['official_data']->note;
                    })
                    
                    ->addColumn('finalized_by', function($transfer){
                        
                        $user = User::where('id', '=', $transfer['official_data']->finalized_by)
                        ->select(
                            'username'
                        )
                        ->first();

                        return $user->username . ' ' . Carbon::parse($transfer['official_data']->finalized_at)->format('d/m/Y h:i A');
                    })

                    ->addColumn('action', function ($transfer) {

                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">';
                        
                        if(in_array('money_transfer.edit', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a href="'.route('money_transfers.edit', $transfer['id']).'" class="dropdown-item edit-item-btn">
                                                    <i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit
                                                </a>
                                            </li>';
                        }

                        if(in_array('money_transfer.delete', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a class="dropdown-item edit-item-btn" style="cursor:pointer" id="deleteBtn" data-id="'.$transfer['id'].'">
                                                   <i class="ri-delete-bin-2-fill align-bottom me-2 text-danger"></i>  Delete
                                                </a>
                                            </li>';
                        }

                        $edit_button .= '</ul>
                                        </div>';

                        return $edit_button;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
                }

            return view('admin.financials.money_transfer.pending');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            DB::beginTransaction();
            try {
                $money_transfer = MoneyTransfer::find($request->transfer_id);
                
                $account_statement = AccountStatement::where('type', '=', 'Money Transfer')
                ->where('reference_id', '=', $request->transfer_id)
                ->get();

                foreach ($account_statement as $statement) {
                    $statement->delete();
                }

                $transaction_pool = TransactionPool::where('poolable_type', '=', 'App\Models\MoneyTransfer')
                ->where('poolable_id', '=', $request->transfer_id)
                ->get();

                foreach ($transaction_pool as $item) {
                    if($item != null)
                    {
                        $item->delete();
                    }
                }

                $money_transfer->delete();

                DB::commit();

                return response()->json(['success' => 'Money Transfer deleted successfully.']);
            } catch (\Throwable $th) {

                DB::rollback();
                dd($th->getMessage());

                return response()->json(['error' => 'Something went wrong.']);
            }
        }
    }
}
