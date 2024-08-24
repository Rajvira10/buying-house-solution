<?php

namespace App\Http\Controllers\Admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\AccountStatement;
use App\Http\Controllers\Controller;
use App\Services\AccountStatementService;

class AccountStatementController extends Controller
{
    private $account_statement_service;

    public function __construct(AccountStatementService $account_statement_service)
    {
        $this->account_statement_service = $account_statement_service;
    }

    public function index(Request $request)
    {
        if (!in_array('account_statement.index', session('user_permissions'))) {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.accounts.account_statement.index');

        $accounts = Account::with('account_category')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account')
                    ->where('name', '!=', 'Client Account');
            })
            ->get();
                    
        try {
            if ($request->ajax()) {

                if ($request->account_id) {

                    $account_statements = $this->account_statement_service->get($request->account_id);

                    $filtered_statements = $account_statements->filter(function ($statement) use($request){

                        $statement_date = Carbon::createFromFormat('d/m/Y', $statement->statement_date)->format('Y-m-d');

                        return $statement_date >= $request->start_date && $statement_date <= $request->end_date;
                    });

                    $filtered_statements = $filtered_statements->values()->all();

                } else {
                    $filtered_statements = [];
                }
            
            return DataTables::of($filtered_statements)
                ->make(true);
        }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
            

        return view('admin.accounts.account_statement.index', compact('accounts'));
    }

    public function balanceSheet(Request $request)
    {
        if (!in_array('account_statement.index', session('user_permissions'))) {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.accounts.account_statement.balance_sheet');

        try 
        {
            if ($request->ajax()) 
            {

                $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
                $end_date = Carbon::now()->endOfMonth()->endOfDay();

                if ($request->has('start_date') && $request->has('end_date')) {
                    $start_date = $request->start_date;

                    if ($request->end_date ) {
                        $end_date = Carbon::parse($request->end_date)->endOfDay();
                    }
                }

                $account_statements = $this->account_statement_service->balanceSheet();

                $filtered_statements = $account_statements->filter(function ($statement) use($request)
                {
                    $statement_date = Carbon::createFromFormat('d/m/Y', $statement->statement_date)->format('Y-m-d');

                    return $statement_date >= $start_date && $statement_date <= $end_date;
                });

                $filtered_statements = $filtered_statements->values()->all();
            
                return DataTables::of($filtered_statements)
                    ->make(true);
            }            
        } catch (\Throwable $th) 
        {
            dd($th->getMessage());
        }

        return view('admin.accounts.account_statement.balance_sheet');
    }


}
