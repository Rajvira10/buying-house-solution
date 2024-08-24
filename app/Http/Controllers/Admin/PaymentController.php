<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\AccountStatement;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionPoolService;

class PaymentController extends Controller
{
    private $transaction_pool_service;

    public function __construct(TransactionPoolService $transaction_pool_service)
    {
        $this->transaction_pool_service = $transaction_pool_service;
    }

    public function index(Request $request)
    {
        if(!in_array('payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.payments.payment.index');

        try {
            if($request->ajax()){

                $warehouseId = session('user_warehouse')->id;

                $payments = Payment::with('account', 'paymentable')
                    ->where('status', 'Approved')
                    ->whereIn('paymentable_type', ['App\Models\Customer', 'App\Models\Supplier'])
                    ->whereHasMorph('paymentable', ['App\Models\Customer', 'App\Models\Supplier'], function ($query) use ($warehouseId) {
                        $query->where('warehouse_id', $warehouseId);
                    })
                    ->where('finalized_by', '=', auth()->user()->id)
                    ->latest()
                    ->get();

                return DataTables::of($payments)
                    ->addColumn('date', function($payment){
                        return Carbon::parse($payment->date)->format('d/m/Y');
                    })

                    ->addColumn('account', function($payment){
                        return $payment->account->name;
                    })

                    ->addColumn('payment_type', function($payment){
                        if($payment->paymentable_type == 'App\Models\Purchase'){
                            $payment_type = 'Purchase';
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale'){
                            $payment_type = 'Sale';
                        }
                        else if($payment->paymentable_type == 'App\Models\Wholesale'){
                            $payment_type = 'Wholesale';
                        }
                        else if($payment->paymentable_type == 'App\Models\Customer'){
                            $payment_type = 'Customer Payment';
                        }
                        else if($payment->paymentable_type == 'App\Models\Supplier'){
                            $payment_type = 'Supplier Payment';
                        }
                        return $payment_type;
                    })

                    ->addColumn('transaction_type', function($payment){
                        if($payment->paymentable_type == 'App\Models\Purchase'){
                            $transaction_type = 'Debit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale'){
                            $transaction_type = 'Credit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Wholesale'){
                            $transaction_type = 'Credit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Customer'){
                            $transaction_type = $payment->amount > 0 ? 'Credit' : 'Debit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Supplier'){
                            $transaction_type = $payment->amount > 0 ? 'Credit' : 'Debit';
                        }
                        return $transaction_type;
                    })
                    
                    ->addColumn('name', function($payment){
                        
                        if($payment->paymentable_type == 'App\Models\Supplier' || $payment->paymentable_type == 'App\Models\Customer'){
                            $name = $payment->paymentable->name;
                        }
                        else if($payment->paymentable_type == 'App\Models\Purchase'){
                            $name = $payment->paymentable->supplier->name;
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale' || $payment->paymentable_type == 'App\Models\Wholesale'){
                            $name = $payment->paymentable->customer->name;
                        }
                        
                        return $name;
                    })

                    ->addColumn('contact_no', function($payment){
                        
                        if($payment->paymentable_type == 'App\Models\Supplier' || $payment->paymentable_type == 'App\Models\Customer'){
                            $contact_no = $payment->paymentable->primary_contact_no . ', ' . $payment->paymentable->secondary_contact_no;
                        }
                        else if($payment->paymentable_type == 'App\Models\Purchase'){
                            $contact_no = $payment->paymentable->supplier->primary_contact_no . ', ' . $payment->paymentable->supplier->secondary_contact_no; 
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale' || $payment->paymentable_type == 'App\Models\Wholesale'){
                            $contact_no = $payment->paymentable->customer->primary_contact_no . ', ' . $payment->paymentable->customer->secondary_contact_no;
                        }
                        
                        return $contact_no;
                    })

                    ->addColumn('amount', function($payment){
                        return number_format(abs($payment->amount), 2, '.', ',');
                    })

                    ->addColumn('action', function ($payment) {

                        $edit_button = '<div class="dropdown d-inline-block">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">';
                        
                        if(in_array('payment.edit', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a href="' . route('payments.edit', $payment->id) . '" class="dropdown-item edit-item-btn">
                                                    <i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit
                                                </a>
                                            </li>';
                        }

                        if(in_array('payment.delete', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <button type="submit" class="dropdown-item delete-item-btn" onclick="deletePayment(' . $payment->id . ')">
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

            return view('admin.payments.payment.index');

        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('payments.index')->with('error', 'Something Went Wrong');
            
        }
    
    }

    public function create(Request $request)
    {
        if(!in_array('payment.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.payments.payment.index');

        $suppliers = Supplier::where('warehouse_id', '=', session('user_warehouse')->id)->get();

        $customers = Customer::where('warehouse_id', '=', session('user_warehouse')->id)->get();

        $accounts = Account::join('account_categories as ac', 'account_category_id', '=', 'ac.id')
            ->where('ac.name', '!=', 'Client Account')
            ->where('ac.name', '!=', 'Supplier Account')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->select(
                'accounts.id',
                'accounts.name'
            )
            ->get();


        return view('admin.payments.payment.create', compact('suppliers', 'customers', 'accounts'));
    }

    public function edit(Request $request, $payment_id)
    {
        if(!in_array('payment.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.payments.payment.index');

        $payment = Payment::findOrFail($payment_id);

        if($payment->paymentable_type == 'App\Models\Sale' || $payment->paymentable_type == 'App\Models\Wholesale')
        {
            $payment->supplier_customer = 'Customer';
            $payment->supplier_customer_id = $payment->paymentable->customer_id;
        }
        else if($payment->paymentable_type == 'App\Models\Customer')
        {
            $payment->supplier_customer = 'Customer';
            $payment->supplier_customer_id = $payment->paymentable->id;
        }
        else if($payment->paymentable_type == 'App\Models\Supplier'){
            $payment->supplier_customer = 'Supplier';
            $payment->supplier_customer_id = $payment->paymentable->id;
        }
        else if($payment->paymentable_type == 'App\Models\Purchase'){
            $payment->supplier_customer = 'Supplier';
            $payment->supplier_customer_id = $payment->paymentable->supplier_id;
        }

        $suppliers = Supplier::where('warehouse_id', '=', session('user_warehouse')->id)->get();

        $customers = Customer::where('warehouse_id', '=', session('user_warehouse')->id)->get();

        $accounts = Account::join('account_categories as ac', 'account_category_id', '=', 'ac.id')
            ->where('ac.name', '!=', 'Client Account')
            ->where('ac.name', '!=', 'Supplier Account')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->select(
                'accounts.id',
                'accounts.name'
            )
            ->get();

        return view('admin.payments.payment.edit', compact('payment', 'suppliers', 'customers', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'amount' => 'required',
            'supplier_id' => 'nullable',
            'customer_id' => 'nullable',
            'transaction_type' => 'required',
            'date' => 'required',
            'note' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $payment = new Payment();

            $payment->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            if($request->transaction_type == 'Receivable'){
                $payment->amount = $request->amount;
            }
            else{
                $payment->amount = $request->amount * (-1);
            }

            $payment->status = 'Pending';

            $payment->account_id = $request->account_id;

            $payment->note = $request->note;

            $payment->finalized_by = Auth::user()->id;

            $payment->finalized_at = Carbon::now();

            $payment->save();

            $payment->payment_no = 'Payt-'. ($payment->id+1000);

            if($request->supplier_id != null)
            {
                $payment->paymentable_type = 'App\Models\Supplier';
                $payment->paymentable_id = $request->supplier_id;
            }
            else if($request->customer_id != null)
            {
                $payment->paymentable_type = 'App\Models\Customer';
                $payment->paymentable_id = $request->customer_id;
            }

            $payment->save();

            $this->transaction_pool_service->tempStorePayment($request, $payment);

            DB::commit();

            return redirect()->route('payments.index')->with('success', 'Payment sent for approval successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th->getMessage());
            return redirect()->route('payments.index')->with('error', 'Something went wrong');
        }
        
    }

    public function update(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'amount' => 'required',
            'supplier_customer' => 'required',
            'supplier_customer_id' => 'required',
            'transaction_type' => 'required',
            'date' => 'required',
            'note' => 'nullable',
        ]);


        DB::beginTransaction();

        try {
            $payment = Payment::findOrFail($request->payment_id);

            $payment->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            if($request->transaction_type == 'Receivable'){
                $payment->amount = $request->amount;
            }
            else{
                $payment->amount = $request->amount * (-1);
            }

            $payment->account_id = $request->account_id;

            $payment->status = 'Pending';

            $payment->note = $request->note;

            $payment->finalized_by = Auth::user()->id;

            $payment->finalized_at = Carbon::now();

            $payment->save();

            $payment->paymentable_type = $request->supplier_customer == 'Customer' ? 'App\Models\Customer' : 'App\Models\Supplier';

            $payment->paymentable_id = $request->supplier_customer_id;

            $payment->save();

            $this->transaction_pool_service->tempStorePayment($request, $payment);

            DB::commit();

            return redirect()->route('payments.index')->with('success', 'Payment sent for approval successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th->getMessage());
            return redirect()->route('payments.index')->with('error', 'Something went wrong');
        }
    }

    public function pending(Request $request)
    {
        if(!in_array('payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.payments.payment.index');

        try {
            if($request->ajax()){

                $warehouseId = session('user_warehouse')->id;

                $payments = Payment::with('account', 'paymentable')
                    ->where('status', 'Pending')
                    ->whereIn('paymentable_type', ['App\Models\Customer', 'App\Models\Supplier'])
                    ->whereHasMorph('paymentable', ['App\Models\Customer', 'App\Models\Supplier'], function ($query) use ($warehouseId) {
                        $query->where('warehouse_id', $warehouseId);
                    })
                    ->latest()
                    ->get();

                return DataTables::of($payments)
                    ->addColumn('date', function($payment){
                        return Carbon::parse($payment->date)->format('d/m/Y');
                    })

                    ->addColumn('payment_type', function($payment){
                        if($payment->paymentable_type == 'App\Models\Purchase'){
                            $payment_type = 'Purchase';
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale'){
                            $payment_type = 'Sale';
                        }
                        else if($payment->paymentable_type == 'App\Models\Wholesale'){
                            $payment_type = 'Wholesale';
                        }
                        else if($payment->paymentable_type == 'App\Models\Customer'){
                            $payment_type = 'Customer Payment';
                        }
                        else if($payment->paymentable_type == 'App\Models\Supplier'){
                            $payment_type = 'Supplier Payment';
                        }
                        return $payment_type;
                    })

                    ->addColumn('transaction_type', function($payment){
                        if($payment->paymentable_type == 'App\Models\Purchase'){
                            $transaction_type = 'Debit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale'){
                            $transaction_type = 'Credit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Wholesale'){
                            $transaction_type = 'Credit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Customer'){
                            $transaction_type = $payment->amount > 0 ? 'Credit' : 'Debit';
                        }
                        else if($payment->paymentable_type == 'App\Models\Supplier'){
                            $transaction_type = $payment->amount > 0 ? 'Credit' : 'Debit';
                        }
                        return $transaction_type;
                    })
                    
                    ->addColumn('name', function($payment){
                        
                        if($payment->paymentable_type == 'App\Models\Supplier' || $payment->paymentable_type == 'App\Models\Customer'){
                            $name = $payment->paymentable->name;
                        }
                        else if($payment->paymentable_type == 'App\Models\Purchase'){
                            $name = $payment->paymentable->supplier->name;
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale' || $payment->paymentable_type == 'App\Models\Wholesale'){
                            $name = $payment->paymentable->customer->name;
                        }
                        
                        return $name;
                    })

                    ->addColumn('contact_no', function($payment){
                        
                        if($payment->paymentable_type == 'App\Models\Supplier' || $payment->paymentable_type == 'App\Models\Customer'){
                            $contact_no = $payment->paymentable->primary_contact_no . ', ' . $payment->paymentable->secondary_contact_no;
                        }
                        else if($payment->paymentable_type == 'App\Models\Purchase'){
                            $contact_no = $payment->paymentable->supplier->primary_contact_no . ', ' . $payment->paymentable->supplier->secondary_contact_no; 
                        }
                        else if($payment->paymentable_type == 'App\Models\Sale' || $payment->paymentable_type == 'App\Models\Wholesale'){
                            $contact_no = $payment->paymentable->customer->primary_contact_no . ', ' . $payment->paymentable->customer->secondary_contact_no;
                        }
                        
                        return $contact_no;
                    })

                    ->addColumn('amount', function($payment){
                        return number_format(abs($payment->amount), 2, '.', ',');
                    })


                    ->addColumn('action', function ($payment) {
                        $edit_button = '<a href="#">
                                            <button class="btn btn-sm btn-success edit-item-btn">
                                                Edit
                                            </button>
                                        </a>';
                        return $edit_button;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
                    
                    }

                return view('admin.payments.payment.pending');

            } catch (\Throwable $th) {
                
                    return redirect()->route('payments.pending')->with('error', 'Something Went Wrong');
                    
            }

    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            DB::beginTransaction();

            try {
                $payment = Payment::findOrFail($request->payment_id);

                $payment->transactionPool()->delete();

                $account_statements = AccountStatement::where('type', '=', 'Payment')
                ->where('reference_id', '=', $payment->id)
                ->get();

                foreach($account_statements as $account_statement)
                {
                    $account_statement->delete();
                }
            
                $payment->delete();

                DB::commit();

                return response()->json(['success' => 'Payment deleted successfully']);
            } catch (\Throwable $th) {
                DB::rollback();
                dd($th->getMessage());
                return response()->json(['error' => 'Something went wrong']);
            }
        }
    }
}
