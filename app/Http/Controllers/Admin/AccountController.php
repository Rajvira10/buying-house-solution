<?php

namespace App\Http\Controllers\Admin;

use DB;
use DataTables;
use App\Models\Account;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\AccountCategory;
use App\Http\Controllers\Controller;
use App\Services\AccountStatementService;

class AccountController extends Controller
{
    private $account_statement_service;

    public function __construct(AccountStatementService $account_statement_service)
    {
        $this->account_statement_service = $account_statement_service;
    }

    public function index(Request $request)
    {
        if(!in_array('account.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.accounts.account.index');

        try{
            if($request->ajax()){

                $accounts = Account::join('account_categories', 'accounts.account_category_id', '=', 'account_categories.id')
                ->join('warehouses', 'accounts.warehouse_id', '=', 'warehouses.id')
                ->select(
                    'accounts.*', 
                    'account_categories.name as category_name',
                    'warehouses.name as warehouse_name'
                )
                ->whereHas('account_category', function ($query) {
                    $query->where('name', '!=', 'Supplier Account');
                    $query->where('name', '!=', 'Client Account');
                })
                ->where('warehouse_id', '=', session('user_warehouse')->id)
                ->get();

                return DataTables::of($accounts)

                    ->addColumn('category_name', function ($account) {
                            return $account->category_name;
                        })
                    ->addColumn('action', function ($account) {
                            if(in_array('account.edit', session('user_permissions')))
                            {
                                $edit_button = '<a href="' . route('accounts.edit', $account->id) . '">
                                                    <button class="btn btn-sm btn-success edit-item-btn">
                                                        Edit
                                                    </button>
                                                </a>';
                            }
                            else{
                                $edit_button = '';
                            }

                            return $edit_button;
                        })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'balance'])
                    ->make(true);
                }

            return view('admin.accounts.account.index');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function create(Request $request)
    {
        if(!in_array('account.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.accounts.account.index');

        try {
            $account_categories = AccountCategory::select(
                'id',
                'name'
            )
            ->where('name', '!=', 'Supplier Account')
            ->where('name', '!=', 'Client Account')
            ->get();

            return view('admin.accounts.account.create', compact('account_categories'));
        } catch (\Throwable $th) {
            return redirect()->route('accounts.index')->with('error', 'Something Went Wrong');
        }

    }


    public function edit(Request $request, $id)
    {
        if(!in_array('account.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.accounts.account.index');

        try {
            $account = Account::where('id', '=', $id)->select(
                'id',
                'name',
                'account_category_id'
            )->get();

            if(!empty($account)){
                $account = $account[0];
                
                $account_categories = AccountCategory::select(
                    'id',
                    'name'
                )
                ->where('name', '!=', 'Supplier Account')
                ->where('name', '!=', 'Client Account')
                ->get();
                
            }
            else{
                return redirect()->route('accounts.index')->with('error', 'Account Not Found');
            }

            return view('admin.accounts.account.edit', compact('account', 'account_categories'));
    
        } catch (\Throwable $th) {
            return redirect()->route('accounts.index')->with('error', 'Something Went Wrong');
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:accounts,name',
                'account_category_id' => 'required|exists:account_categories,id',
                'opening_balance' => 'numeric|min:0|nullable'
            ]
        );
        
        DB::beginTransaction();

        try {
            
            $account = new Account();

            $account->name = $request->name;

            $account->warehouse_id = session('user_warehouse')->id;

            $account->account_category_id = $request->account_category_id;

            $account->save();

            $this->account_statement_service->setInitialOpeningBalance($account->id, $request->opening_balance);

            DB::commit();

            return redirect()->route('accounts.index')->with('success', 'Account Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('accounts.index')->with('error', $th->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|unique:accounts,name,' .$id,
                'account_category_id' => 'required',
            ]
        );

        try {
            $account = Account::where('id', '=', $id)->select(
                'id',
                'name',
                'account_category_id'
            )->get();
            
            if (!empty($account)) {

                $account = $account[0];

                $account->name = $request->name;

                $account->account_category_id = $request->account_category_id;

                $account->save();

                return redirect()->route('accounts.index')->with('success', 'Account Updated Successfully');
            }
            else{
                return redirect()->route('accounts.index')->with('error', 'Account Not Found');
            }
        } catch (\Throwable $th) {
            return redirect()->route('accounts.index')->with('error', 'Something Went Wrong');
        }
    }

    public function destroy($id)
    {
        //   
    }

    public function getAccountsByWarehouse(Request $request)
    {
        if($request->ajax()){

            $warehouse_id = $request->warehouse_id;

            $accounts = Account::whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account');
                $query->where('name', '!=', 'Client Account');
            })
            ->where('warehouse_id', '=', $warehouse_id)
            ->get();

            return response()->json($accounts);
        }
    }

    public function getCurrentBalance(Request $request)
    {
        if($request->ajax())
        {
            $current_balance = $this->account_statement_service->getCurrentBalance($request->account_id);

            return response()->json(['current_balance' => $current_balance]);
        }
    }
}
