<?php

namespace App\Http\Controllers\Admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Employee;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Models\TransactionPool;
use App\Models\AccountStatement;
use App\Http\Controllers\Controller;
use App\Services\NumberToWordsService;
use App\Services\TransactionPoolService;

class ExpenseController extends Controller
{
    private $transaction_pool_service;
    private $number_to_words_service;

    public function __construct(TransactionPoolService $transaction_pool_service, NumberToWordsService $number_to_words_service)
    {
        $this->transaction_pool_service = $transaction_pool_service;
        $this->number_to_words_service = $number_to_words_service;
    }

    public function index(Request $request)
    {
        if(!in_array('expense.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.expenses.expense.index');
        
        try{
            if($request->ajax()){
                
                if(in_array('see_everything', session('user_permissions')))
                {
                    $expenses = Expense::join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
                    ->select(
                        'expenses.*', 
                        'expense_categories.name as category_name', 
                        )
                    ->where('expenses.warehouse_id', '=', session('user_warehouse')->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                }
                else
                {
                    $expenses = Expense::join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
                    ->select(
                        'expenses.*', 
                        'expense_categories.name as category_name', 
                        )
                    ->where('expenses.warehouse_id', '=', session('user_warehouse')->id)
                    ->where('finalized_by', '=', auth()->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();        
                }    
                
                return DataTables::of($expenses)

                    ->addColumn('date', function($expense){

                        return Carbon::parse($expense->date)->format('d/m/Y');

                    })

                    ->addColumn('employee_name', function($expense){
    
                        if($expense->employee != null)
                        return $expense->employee->name;
                        else
                        return "";

                    })

                    ->addColumn('payment_status', function($expense){

                        if($expense->payment_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($expense->payment_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('total_paid', function($expense){
                            
                            $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount');
    
                            return $total_paid;
    
                    })

                    ->addColumn('due', function($expense){
                            
                        $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount');

                        $expense_amount = floatval($expense->amount);
                        
                        $due = $expense_amount - $total_paid;

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
                    
                    ->addColumn('category_name', function ($expense) {

                            return $expense->category_name;

                    })

                    ->addColumn('action', function ($expense) {

                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">';
                        
                        if(in_array('expense.print', session('user_permissions')))
                        {
                            $edit_button .= '<li><a href="' . route('expenses.print_voucher', $expense->id) . '" class="dropdown-item edit-item-btn"><i class="ri-printer-fill align-bottom me-2 text-primary"></i> Print Voucher</a></li>';
                        }

                        if(in_array('expense.edit', session('user_permissions')))
                        {
                            $edit_button .= '<li><a href="' . route('expenses.edit', $expense->id) . '" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>';
                        }

                        if(in_array('expense.payment.index', session('user_permissions')))
                        {
                            // $edit_button .= '<li><a href="'. route('expenses.pending_payments', $expense->id).'" class="dropdown-item edit-item-btn"><i class="ri-time-fill align-bottom me-2 text-warning"></i> Pending Payments</a></li>';
                            $edit_button .= '<li><a href="'. route('expenses.approved_payments', $expense->id).'" class="dropdown-item edit-item-btn"><i class="ri-eye-fill align-bottom me-2 text-success"></i> View Payments</a></li>';
                        }

                        if(in_array('expense.payment.create', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a href="'.route('expenses.payment', $expense->id).'" class="dropdown-item remove-item-btn">
                                                    <i class="ri-currency-fill align-bottom me-2 text-info"></i> Make Payment
                                                </a>
                                            </li>';
                        }

                        if(in_array('expense.delete', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteExpense(' . $expense->id . ')">
                                                    <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                                </button>
                                            </li>';
                        }

                        $edit_button .= '</ul>
                                        </div>';

                        return $edit_button;
                    })
                    ->rawColumns(['action', 'payment_status'])
                    ->make(true);
                }

            return view('admin.expenses.expense.index');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function create(Request $request)
    {
        if(!in_array('expense.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.expenses.expense.index');

        $expense_categories = ExpenseCategory::select(
            'id',
            'name'
        )->get();

        $employees = Employee::select(
            'id',
            'name'
        )->get();

        return view('admin.expenses.expense.create', compact('expense_categories', 'employees'));
    }


    public function edit(Request $request, $expense_id)
    {
        if(!in_array('expense.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.expenses.expense.index');

        $expense = Expense::find($expense_id);

        if($expense != null){
            
            $employees = Employee::select(
                'id',
                'name'
            )->get();
              
            $expense_categories = ExpenseCategory::select(
                'id',
                'name'
            )->get();
        }
        else{
            return redirect()->route('expenses.index')->with('error', 'Expense Not Found');
        }

        return view('admin.expenses.expense.edit', compact('expense', 'expense_categories', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'expense_category_id' => 'required|numeric',
                'amount' => 'required|numeric',
                'date' => 'required',
                'note' => 'nullable'
            ]
        );

        DB::beginTransaction();

        try {

            $expense = new Expense();

            $expense->warehouse_id = session('user_warehouse')->id;

            $expense->expense_category_id = $request->expense_category_id;

            $expense->amount = $request->amount;

            $expense->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $expense->note = $request->note;

            $expense->payment_status = 'Due';

            $expense->finalized_by = auth()->user()->id;

            $expense->finalized_at = Carbon::now()->toDateTimeString();

            $expense->save();

            $expense->expense_no = 'Exp-'. ($expense->id+1000);

            $expense->save();


            DB::commit();

            return redirect()->route('expenses.index')->with('success', 'Expense Has Been Created Successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('expenses.index')->with('error', $bug);
        }
    }

    public function update(Request $request, $expense_id)
    {
        $request->validate(
            [
                'expense_category_id' => 'required',
                'amount' => 'required',
                'date' => 'required',
            ]
        );

        DB::beginTransaction();

        try {
            $expense = Expense::find($expense_id);

            if ($expense != null) {

                $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount');

                if($request->amount < $total_paid)
                {
                    return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than ' . $total_paid . ' as payment has been done']);
                }

                $expense->warehouse_id = session('user_warehouse')->id;

                $expense->expense_category_id = $request->expense_category_id;


                $expense->amount = $request->amount;

                $expense->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

                $expense->note = $request->note;


                $expense_amount = floatval($expense->amount);

                if($total_paid == 0)
                {
                    $expense->payment_status = 'Due';
                }
                else if($total_paid < $expense_amount)
                {
                    $expense->payment_status = 'Partial';
                }
                else
                {
                    $expense->payment_status = 'Paid';
                }

                $expense->save();

                DB::commit();

                return redirect()->route('expenses.index')->with('success', 'Expense Updated Successfully');
            }
            else{
                return redirect()->route('expenses.index')->with('error', 'Expense Not Found');
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('expenses.index')->with('error', $bug);
        }
    }

    public function destroy(Request $request)
    {

        DB::beginTransaction();

        if($request->ajax())
        {
            try {
                $expense = Expense::find($request->expense_id);

                if($expense != null)
                {
                    $transaction_pools = $expense->payments()->with('transactionPool')->get()->pluck('transactionPool')->flatten()->unique('id');

                    $transaction_pools->each(function($item){
                        if($item != null)
                        {
                            $item->delete();
                        }
                    });

                    
                    $expense->payments()->each(function($payment){
                        AccountStatement::where('reference_id', '=', $payment->id)
                            ->where('type', '=', 'Expense')
                            ->delete();
                    });


                    $expense->payments()->delete();

                    $expense->delete();

                    DB::commit();

                    return response()->json(['success'=>'Expense Deleted Successfully']);
                }
                else{
                    dd('here');

                    return response()->json(['error'=>'Expense Not Found']);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $bug = $th->getMessage();
                return response()->json(['error'=>$bug]);
            }
        }
    }

    public function pending(Request $request)
    {
        if(!in_array('expense.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.expenses.expense.index');

        try{
            if($request->ajax()){
                
                $expenses = TransactionPool::select(
                    'data'
                )
                ->where('poolable_type', '=', 'App\Models\Expense')
                ->where(function ($query) {
                    $query->where('checked_by', '=', null)
                        ->orWhere('approved_by', '=', null);
                })
                ->get();
                
                $expenses = $expenses->map(function($item){
                    return json_decode($item->data)->official_data;
                });

                $expenses = $expenses->map(function($item){

                    $expense_category = ExpenseCategory::where('id', '=', $item->expense_category_id)->select(
                        'name'
                    )->first();

                    $item->category_name = $expense_category->name;

                    return $item;
                
                });

                return DataTables::of($expenses)

                    ->addColumn('date', function($expense){

                        return $expense->date;

                    })

                    ->addColumn('warehouse_name', function($expense){

                        $warehouse = Warehouse::find($expense->warehouse_id);

                        return $warehouse->name;

                    })

                    ->addColumn('payment_status', function($expense){

                        if($expense->payment_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($expense->payment_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('finalized_by', function($expense){
                        
                        $user = User::where('id', '=', $expense->finalized_by)
                        ->select(
                            'first_name'
                        )
                        ->first();

                        return $user->first_name . ' ' . Carbon::parse($expense->finalized_at)->format('d/m/Y h:i A');
                    })

                    ->rawColumns(['payment_status'])
                    ->make(true);
                }

            return view('admin.expenses.expense.pending');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function pendingPayments(Request $request, $expense_id)
    {
        if(!in_array('expense.payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.expenses.expense.index');

        try{
            $expense = Expense::find($expense_id);
            
            if($request->ajax()){

                $payments = Payment::where('paymentable_type', '=', 'App\Models\Expense')
                ->where('paymentable_id', '=', $expense->id)
                ->where('status', '=', 'Pending')
                ->get();

                return DataTables::of($payments)
                
                ->addColumn('date', function($payment){

                    return Carbon::parse($payment->date)->format('d/m/Y');

                })

                ->addColumn('account', function($payment){

                    $account = Account::find($payment->account_id);

                    return $account->name;

                })

                ->addColumn('finalized_by', function($payment){
                    
                    $user = User::where('id', '=', $payment->finalized_by)
                    ->select(
                        'first_name'
                    )
                    ->first();

                    return $user->first_name . ' ' . Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');
                })
                ->make(true);
                   
                    
            }
            return view('admin.expenses.expense.pending_payments', compact('expense'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function approvedPayments(Request $request, $expense_id)
    {
        if(!in_array('expense.payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.expenses.expense.index');

        try{
            $expense = Expense::find($expense_id);

            $payments = $expense->payments()->where('status', '=', 'Approved')->get();

            $total_paid = $payments->sum('amount');

            $expense_amount = floatval($expense->amount);
            
            $due = $expense_amount - $total_paid;
            
            if($request->ajax()){

                return DataTables::of($payments)
                
                ->addColumn('date', function($payment){

                    return Carbon::parse($payment->date)->format('d/m/Y');

                })

                ->addColumn('account', function($payment){

                    $account = Account::find($payment->account_id);

                    return $account->name;

                })

                ->addColumn('finalized_by', function($payment){
                    
                    $user = User::where('id', '=', $payment->finalized_by)
                    ->select(
                        'first_name'
                    )
                    ->first();

                    return $user->first_name . ' ' . Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');
                    })
                
                ->addColumn('action', function ($payment) {

                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="'. route('expenses.edit_payment', $payment->id) .'" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        </ul>
                                    </div>';
                                    
                    return $edit_button;
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
            return view('admin.expenses.expense.approved_payments', compact('expense', 'due'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function payment(Request $request, $expense_id)
    {
        if(!in_array('expense.payment.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.expenses.expense.index');

        $expense = Expense::find($expense_id);

        if($expense != null){

            $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount');

            $expense_amount = floatval($expense->amount);
            
            $due = $expense_amount - $total_paid;

            $accounts = Account::where('warehouse_id', '=', $expense->warehouse_id)
            ->whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account');
                $query->where('name', '!=', 'Client Account');
            })->get();


            return view('admin.expenses.expense.payment', compact('expense', 'accounts', 'due'));
        }
        else{
            return redirect()->route('expenses.index')->with('error', 'Expense Not Found');
        }
    }

    public function storePayment(Request $request, $expense_id)
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

            $expense = Expense::find($expense_id);

            $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount');

            $expense_amount = floatval($expense->amount);
            
            $due = $expense_amount - $total_paid;

            if($request->amount > $due)
            {
                 return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be more than ' . $due]);
            }
            else if($request->amount < 1)
            {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than 1']);
            }

            $payment = new Payment();

            $payment->paymentable_type = 'App\Models\Expense';

            $payment->paymentable_id = $expense_id;

            $payment->amount = $request->amount;
            
            $payment->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $payment->account_id = $request->account_id;

            $payment->note = $request->note;

            $payment->status = 'Pending';

            $payment->finalized_by = auth()->user()->id;

            $payment->finalized_at = Carbon::now()->toDateTimeString();

            $payment->save();

            $payment->payment_no = 'Payt-'. ($payment->id+1000);

            $payment->save();
            
            $this->transaction_pool_service->tempStoreExpensePayment($payment);

            DB::commit();

            return redirect()->route('expenses.index')->with('success', 'Payment Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('expenses.index')->with('error', $bug);
        }
    }

    public function editPayment(Request $request, $payment_id)
    {
        if(!in_array('expense.payment.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.expenses.expense.index');

        $payment = Payment::find($payment_id);

        $expense = $payment->paymentable;

        $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount');

        $expense_amount = floatval($expense->amount);
        
        $due = $expense_amount - $total_paid;

        $accounts = Account::whereHas('account_category', function ($query) {
            $query->where('name', '!=', 'Supplier Account');
            $query->where('name', '!=', 'Client Account');
        })
        ->where('warehouse_id', '=', $expense->warehouse_id)
        ->get();

        return view('admin.expenses.expense.edit_payment', compact('expense','payment', 'accounts', 'due'));
    }


    public function updatePayment(Request $request, $payment_id)
    {
        $request->validate(
            [
                'account_id' => 'required|numeric|exists:accounts,id',
                'paying_amount' => 'required|numeric',
                'note' => 'nullable'
            ]
        );

        DB::beginTransaction();

        try {
            
            $payment = Payment::find($payment_id);

            $expense = $payment->paymentable;

            $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount') - $payment->amount;

            $expense_amount = floatval($expense->amount);
           
            $due = $expense_amount - $total_paid;

            if($request->paying_amount > $due)
            {
                 return redirect()->back()->withInput()->withErrors(['paying_amount' => 'Amount can not be more than ' . $due]);
            }

            $payment->amount = $request->paying_amount;
            
            if($request->date != null){
                $payment->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();
            }
            else{
                $payment->date = Carbon::now()->toDateTimeString();
            }
            
            $payment->account_id = $request->account_id;

            $payment->note = $request->note;

            $payment->status = 'Pending';

            $payment->finalized_by = auth()->user()->id;

            $payment->finalized_at = Carbon::now()->toDateTimeString();

            $payment->save();

            $expense->payment_status = $total_paid == 0 ? 'Due' : 'Partial';

            $expense->save();
            
            $this->transaction_pool_service->tempStoreExpensePayment($payment);

            DB::commit();

            return redirect()->route('expenses.index')->with('success', 'Payment Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('expenses.index')->with('error', $bug);

        }

    }

    public function destroyPayment($id)
    {
        //
    }

    public function printVoucher($expense_id)
    {
        if(!in_array('expense.print', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $expense = Expense::find($expense_id);

        $warehouse = Warehouse::find($expense->warehouse_id);

        $amount_in_words = $this->number_to_words_service->convert($expense->amount);

        return view('admin.expenses.expense.print_voucher', compact('expense', 'amount_in_words', 'warehouse'));
    }
}
