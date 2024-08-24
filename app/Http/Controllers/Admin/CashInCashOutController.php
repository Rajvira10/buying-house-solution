<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\CashInCashOut;
use App\Models\AccountStatement;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AccountStatementService;

class CashInCashOutController extends Controller
{
    private $account_statement_service;

    public function __construct()
    {
        $this->account_statement_service = new AccountStatementService();
    }

    public function index(Request $request)
    {
        if(!in_array('add_withdraw_money.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.accounts.cash_in_cash_out.index');

        if($request->ajax()){

            $cash_in_cash_outs = CashInCashOut::with('account')
                ->select(
                    'id',
                    'type',
                    'account_id',
                    'amount',
                    'date',
                    'note',
                    'finalized_by',
                    'finalized_at'
                )
                ->whereHas('account', function ($query) {
                    $query->where('warehouse_id', session('user_warehouse')->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();


            return DataTables::of($cash_in_cash_outs)
                ->addColumn('account_name', function($cash_in_cash_out){
                        
                    $account = $cash_in_cash_out->account;

                    return $account->name;
                    
                })

                ->addColumn('date', function($cash_in_cash_out){

                    return Carbon::parse($cash_in_cash_out->date)->format('d/m/Y');

                })

                ->addColumn('finalized_by', function($cash_in_cash_out){
                    
                    $user = User::where('id', '=', $cash_in_cash_out->finalized_by)
                    ->select(
                        'first_name'
                    )
                    ->first();

                    return $user->first_name . ' ' . Carbon::parse($cash_in_cash_out->finalized_at)->format('d/m/Y h:i A');
                    
                })

                ->addColumn('action', function($cash_in_cash_out){

                    $edit_button = '<a href="'.route('cash_in_cash_outs.edit', $cash_in_cash_out->id).'" class="btn btn-primary btn-sm">Edit</a>';

                    $edit_button .= ' <button type="button" class="btn btn-danger btn-sm" onclick="deleteCashInCashOut('.$cash_in_cash_out->id.')">Delete</button>';

                    return $edit_button;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.accounts.cash_in_cash_out.index');
    }

    public function addMoney(Request $request)
    {
        if(!in_array('add_withdraw_money.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.accounts.cash_in_cash_out.index');

        $accounts = Account::join('account_categories as ac', 'account_category_id', '=', 'ac.id')
                ->where('ac.name', '!=', 'Client Account')
                ->where('ac.name', '!=', 'Supplier Account')
                ->where('warehouse_id', '=', session('user_warehouse')->id)
                ->select(
                    'accounts.*',
                    'ac.name as category_name'
                )
                ->orderByRaw("accounts.name = 'Cash' DESC")
                ->orderBy('accounts.name') 
                ->get();
        return view('admin.accounts.cash_in_cash_out.add_money', compact('accounts'));
    }

    public function addMoneyStore(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',
        ]);

        try {

            DB::beginTransaction();

            $cash_in_cash_out = new CashInCashOut();

            $cash_in_cash_out->type = 'Add Money';

            $cash_in_cash_out->account_id = $request->account_id;

            $cash_in_cash_out->amount = $request->amount;

            $cash_in_cash_out->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $cash_in_cash_out->note = $request->note;

            $cash_in_cash_out->finalized_by = auth()->user()->id;

            $cash_in_cash_out->finalized_at = Carbon::now()->toDateTimeString();

            $cash_in_cash_out->save();

            $account_statement_data_arr = [
                'type' => 'Add Money',
                'reference_id' => $cash_in_cash_out->id,
                'amount' => $cash_in_cash_out->amount,
                'account_id' => $request->account_id,
                'cash_flow_type' => 'Credit',
            ];

            $this->account_statement_service->store($account_statement_data_arr);

            DB::commit();

            return redirect()->route('cash_in_cash_outs.index')->with('success', 'Money Added Successfully');

        } catch (\Throwable $th) {
                
            DB::rollback();

            return redirect()->route('cash_in_cash_outs.index')->with('error', 'Something went wrong, please try again');
        }
    }

    public function withdrawMoney(Request $request)
    {
        if(!in_array('add_withdraw_money.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.accounts.cash_in_cash_out.index');

        $accounts = Account::join('account_categories as ac', 'account_category_id', '=', 'ac.id')
                ->where('ac.name', '!=', 'Client Account')
                ->where('ac.name', '!=', 'Supplier Account')
                ->where('warehouse_id', '=', session('user_warehouse')->id)
                ->select(
                    'accounts.*',
                    'ac.name as category_name'
                )
                ->orderByRaw("accounts.name = 'Cash' DESC")
                ->orderBy('accounts.name') 
                ->get();
        return view('admin.accounts.cash_in_cash_out.withdraw_money', compact('accounts'));
    }

    public function withdrawMoneyStore(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',
        ]);

        try {

            DB::beginTransaction();

            $cash_in_cash_out = new CashInCashOut();

            $cash_in_cash_out->type = 'Withdraw Money';

            $cash_in_cash_out->account_id = $request->account_id;

            $cash_in_cash_out->amount = $request->amount;

            $cash_in_cash_out->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $cash_in_cash_out->note = $request->note;

            $cash_in_cash_out->finalized_by = auth()->user()->id;

            $cash_in_cash_out->finalized_at = Carbon::now()->toDateTimeString();

            $cash_in_cash_out->save();

            $account_statement_data_arr = [
                'type' => 'Withdraw Money',
                'reference_id' => $cash_in_cash_out->id,
                'amount' => $cash_in_cash_out->amount * (-1),
                'account_id' => $request->account_id,
                'cash_flow_type' => 'Debit',
            ];

            $this->account_statement_service->store($account_statement_data_arr);

            DB::commit();

            return redirect()->route('cash_in_cash_outs.index')->with('success', 'Money Withdrawn Successfully');

        } catch (\Throwable $th) {

            DB::rollback();

            return redirect()->route('cash_in_cash_outs.index')->with('error', 'Something went wrong, please try again');
        }

    }

    public function edit(Request $request)
    {
        // if(!in_array('add_withdraw_money.edit', session('user_permissions')))
        // {
        //     return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        // }

        $request->session()->now('view_name', 'admin.accounts.cash_in_cash_out.index');

        $cash_in_cash_out = CashInCashOut::find($request->id);

        $accounts = Account::join('account_categories as ac', 'account_category_id', '=', 'ac.id')
                ->where('ac.name', '!=', 'Client Account')
                ->where('ac.name', '!=', 'Supplier Account')
                ->where('warehouse_id', '=', session('user_warehouse')->id)
                ->select(
                    'accounts.*',
                    'ac.name as category_name'
                )
                ->orderByRaw("accounts.name = 'Cash' DESC")
                ->orderBy('accounts.name') 
                ->get();

        return view('admin.accounts.cash_in_cash_out.edit', compact('cash_in_cash_out', 'accounts'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',
        ]);

        try {

            DB::beginTransaction();

            $cash_in_cash_out = CashInCashOut::find($request->id);

            $cash_in_cash_out->account_id = $request->account_id;

            $cash_in_cash_out->amount = $request->amount;

            $cash_in_cash_out->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $cash_in_cash_out->note = $request->note;

            $cash_in_cash_out->finalized_by = auth()->user()->id;

            $cash_in_cash_out->finalized_at = Carbon::now()->toDateTimeString();

            $cash_in_cash_out->save();

            $account_statement = AccountStatement::where('reference_id', '=', $cash_in_cash_out->id)
                ->where('type', '=', $cash_in_cash_out->type)
                ->first();

            $account_statement->amount = $cash_in_cash_out->amount;

            $account_statement->save();

            DB::commit();

            return redirect()->route('cash_in_cash_outs.index')->with('success', 'Money Updated Successfully');

        } catch (\Throwable $th) {

            DB::rollback();
            dd($th->getMessage());
            return redirect()->route('cash_in_cash_outs.index')->with('error', 'Something went wrong, please try again');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            DB::beginTransaction();

            try {
                    
                $cash_in_cash_out = CashInCashOut::find($request->id);

                $account_statement = AccountStatement::where('reference_id', '=', $cash_in_cash_out->id)
                    ->where('type', '=', $cash_in_cash_out->type)
                    ->first();

                $account_statement->delete();

                $cash_in_cash_out->delete();

                DB::commit();

                return response()->json(['success' => 'Money Deleted Successfully']);

            } catch (\Throwable $th) {

                DB::rollback();

                return response()->json(['error' => 'Something went wrong, please try again']);
            }
        }
    }
}
