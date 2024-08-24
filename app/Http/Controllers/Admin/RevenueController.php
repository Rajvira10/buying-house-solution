<?php

namespace App\Http\Controllers\Admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Revenue;
use App\Models\Payment;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\RevenueCategory;
use App\Models\TransactionPool;
use App\Models\AccountStatement;
use App\Http\Controllers\Controller;
use App\Services\TransactionPoolService;

class RevenueController extends Controller
{
    private $transaction_pool_service;

    public function __construct(TransactionPoolService $transaction_pool_service)
    {
        $this->transaction_pool_service = $transaction_pool_service;
    }

    public function index(Request $request)
    {
        if(!in_array('revenue.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue.index');
        
        try{
            if($request->ajax()){
                
                if(in_array('see_everything', session('user_permissions')))
                {
                    $revenues = Revenue::join('revenue_categories', 'revenues.revenue_category_id', '=', 'revenue_categories.id')
                    ->select(
                        'revenues.*', 
                        'revenue_categories.name as category_name', 
                        )
                    ->where('revenues.warehouse_id', '=', session('user_warehouse')->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                }
                else
                {
                    $revenues = Revenue::join('revenue_categories', 'revenues.revenue_category_id', '=', 'revenue_categories.id')
                    ->select(
                        'revenues.*', 
                        'revenue_categories.name as category_name', 
                        )
                    ->where('revenues.warehouse_id', '=', session('user_warehouse')->id)
                    ->where('finalized_by', '=', auth()->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                }
                
                return DataTables::of($revenues)

                    ->addColumn('date', function($revenue){

                        return Carbon::parse($revenue->date)->format('d/m/Y');

                    })

                    ->addColumn('payment_status', function($revenue){

                        if($revenue->payment_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($revenue->payment_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('total_paid', function($revenue){
                            
                            $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount');
    
                            return $total_paid;
    
                    })

                    ->addColumn('due', function($revenue){
                            
                        $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount');

                        $revenue_amount = floatval($revenue->amount);
                        
                        $due = $revenue_amount - $total_paid;

                        return $due;

                    })


                    ->addColumn('finalized_by', function($revenue){
                        
                        $user = User::where('id', '=', $revenue->finalized_by)
                        ->select(
                            'first_name'
                        )
                        ->first();

                        return $user->first_name . ' ' . Carbon::parse($revenue->finalized_at)->format('d/m/Y h:i A');
                        
                    })
                    
                    ->addColumn('category_name', function ($revenue) {

                            return $revenue->category_name;

                    })

                    ->addColumn('action', function ($revenue) {

                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">';
                        
                        if(in_array('revenue.edit', session('user_permissions')))
                        {
                            $edit_button .= '<li><a href="' . route('revenues.edit', $revenue->id) . '" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-primary"></i> Edit</a></li>';
                        }

                        if(in_array('revenue.payment.index', session('user_permissions')))
                        {
                            $edit_button .= '<li><a href="'. route('revenues.pending_payments', $revenue->id).'" class="dropdown-item edit-item-btn"><i class="ri-time-fill align-bottom me-2 text-warning"></i> Pending Payments</a></li>';
                            $edit_button .= '<li><a href="'. route('revenues.approved_payments', $revenue->id).'" class="dropdown-item edit-item-btn"><i class="ri-eye-fill align-bottom me-2 text-success"></i> View Payments</a></li>';
                        }

                        if(in_array('revenue.payment.create', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <a href="'.route('revenues.payment', $revenue->id).'" class="dropdown-item remove-item-btn">
                                                    <i class="ri-currency-fill align-bottom me-2 text-info"></i> Make Payment
                                                </a>
                                            </li>';
                        }

                        if(in_array('revenue.delete', session('user_permissions')))
                        {
                            $edit_button .= '<li>
                                                <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteRevenue(' . $revenue->id . ')">
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

            return view('admin.revenues.revenue.index');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function create(Request $request)
    {
        if(!in_array('revenue.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue.index');

        $revenue_categories = RevenueCategory::select(
            'id',
            'name'
        )->get();

        return view('admin.revenues.revenue.create', compact('revenue_categories'));
    }


    public function edit(Request $request, $revenue_id)
    {
        if(!in_array('revenue.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue.index');

        $revenue = Revenue::find($revenue_id);

        if($revenue != null){
              
            $revenue_categories = RevenueCategory::select(
                'id',
                'name'
            )->get();
        }
        else{
            return redirect()->route('revenues.index')->with('error', 'Revenue Not Found');
        }

        return view('admin.revenues.revenue.edit', compact('revenue', 'revenue_categories'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'revenue_category_id' => 'required|numeric',
                'amount' => 'required|numeric',
                'date' => 'required',
                'note' => 'nullable'
            ]
        );

        DB::beginTransaction();

        try {
            
            // $this->transaction_pool_service->tempStoreRevenue($request);

            $revenue = new Revenue();

            $revenue->warehouse_id = session('user_warehouse')->id;

            $revenue->revenue_category_id = $request->revenue_category_id;

            $revenue->amount = $request->amount;

            $revenue->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

            $revenue->note = $request->note;

            $revenue->payment_status = 'Due';

            $revenue->finalized_by = auth()->user()->id;

            $revenue->finalized_at = Carbon::now()->toDateTimeString();

            $revenue->save();

            $revenue->revenue_no = 'Exp-'. ($revenue->id+1000);

            $revenue->save();

            DB::commit();

            return redirect()->route('revenues.index')->with('success', 'Revenue Has Been Created Successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('revenues.index')->with('error', $bug);
        }
    }

    public function update(Request $request, $revenue_id)
    {
        $request->validate(
            [
                'revenue_category_id' => 'required',
                'amount' => 'required',
                'date' => 'required',
            ]
        );

        DB::beginTransaction();

        try {
            $revenue = Revenue::find($revenue_id);

            if ($revenue != null) {

                $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount');

                if($request->amount < $total_paid)
                {
                    return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than ' . $total_paid . ' as payment has been done']);
                }

                $revenue->warehouse_id = session('user_warehouse')->id;
                
                $revenue->revenue_category_id = $request->revenue_category_id;

                $revenue->amount = $request->amount;

                $revenue->date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateTimeString();

                $revenue->note = $request->note;


                $revenue_amount = floatval($revenue->amount);

                if($total_paid == 0)
                {
                    $revenue->payment_status = 'Due';
                }
                else if($total_paid < $revenue_amount)
                {
                    $revenue->payment_status = 'Partial';
                }
                else
                {
                    $revenue->payment_status = 'Paid';
                }

                $revenue->save();

                DB::commit();

                return redirect()->route('revenues.index')->with('success', 'Revenue Updated Successfully');
            }
            else{
                return redirect()->route('revenues.index')->with('error', 'Revenue Not Found');
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('revenues.index')->with('error', $bug);
        }
    }

    public function destroy(Request $request)
    {

        DB::beginTransaction();

        if($request->ajax())
        {
            try {
                $revenue = Revenue::find($request->revenue_id);

                if($revenue != null)
                {
                    $transaction_pools = $revenue->payments()->with('transactionPool')->get()->pluck('transactionPool')->flatten()->unique('id');

                    $transaction_pools->each(function($item){
                        if($item != null)
                        {
                            $item->delete();
                        }
                    });


                    $revenue->payments()->each(function($payment){
                        AccountStatement::where('reference_id', '=', $payment->id)
                            ->where('type', '=', 'Revenue')
                            ->delete();
                    });

                    $revenue->payments()->delete();

                    $revenue->delete();

                    DB::commit();

                    return response()->json(['success'=>'Revenue Deleted Successfully']);
                }
                else{
                    dd('here');

                    return response()->json(['error'=>'Revenue Not Found']);
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
        if(!in_array('revenue.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue.index');

        try{
            if($request->ajax()){
                
                $revenues = TransactionPool::select(
                    'data'
                )
                ->where('poolable_type', '=', 'App\Models\Revenue')
                ->where(function ($query) {
                    $query->where('checked_by', '=', null)
                        ->orWhere('approved_by', '=', null);
                })
                ->get();
                
                $revenues = $revenues->map(function($item){
                    return json_decode($item->data)->official_data;
                });

                $revenues = $revenues->map(function($item){

                    $revenue_category = RevenueCategory::where('id', '=', $item->revenue_category_id)->select(
                        'name'
                    )->first();

                    $item->category_name = $revenue_category->name;

                    return $item;
                
                });

                return DataTables::of($revenues)

                    ->addColumn('date', function($revenue){

                        return $revenue->date;

                    })

                    ->addColumn('warehouse_name', function($revenue){

                        $warehouse = Warehouse::find($revenue->warehouse_id);

                        return $warehouse->name;

                    })

                    ->addColumn('payment_status', function($revenue){

                        if($revenue->payment_status == 'Due')
                        {
                            return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                        }
                        else if($revenue->payment_status == 'Partial')
                        {
                            return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                        }
                        else
                        {
                            return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                        }

                    })

                    ->addColumn('finalized_by', function($revenue){
                        
                        $user = User::where('id', '=', $revenue->finalized_by)
                        ->select(
                            'first_name'
                        )
                        ->first();

                        return $user->first_name . ' ' . Carbon::parse($revenue->finalized_at)->format('d/m/Y h:i A');
                    })

                    ->rawColumns(['payment_status'])
                    ->make(true);
                }

            return view('admin.revenues.revenue.pending');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function pendingPayments(Request $request, $revenue_id)
    {
        if(!in_array('revenue.payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue.index');

        try{
            $revenue = Revenue::find($revenue_id);
            
            if($request->ajax()){

                $payments = Payment::where('paymentable_type', '=', 'App\Models\Revenue')
                ->where('paymentable_id', '=', $revenue->id)
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
            return view('admin.revenues.revenue.pending_payments', compact('revenue'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function approvedPayments(Request $request, $revenue_id)
    {
        if(!in_array('revenue.payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.revenues.revenue.index');

        try{
            $revenue = Revenue::find($revenue_id);

            $payments = $revenue->payments()->where('status', '=', 'Approved')->get();

            $total_paid = $payments->sum('amount');

            $revenue_amount = floatval($revenue->amount);
            
            $due = $revenue_amount - $total_paid;
            
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
                                            <li><a href="'. route('revenues.edit_payment', $payment->id) .'" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        </ul>
                                    </div>';
                                    
                    return $edit_button;
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
            return view('admin.revenues.revenue.approved_payments', compact('revenue', 'due'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function payment(Request $request, $revenue_id)
    {
        if(!in_array('revenue.payment.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.revenues.revenue.index');

        $revenue = Revenue::find($revenue_id);

        if($revenue != null){

            $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount');

            $revenue_amount = floatval($revenue->amount);
            
            $due = $revenue_amount - $total_paid;

            $accounts = Account::where('warehouse_id', '=', $revenue->warehouse_id)
            ->whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account');
                $query->where('name', '!=', 'Client Account');
            })->get();


            return view('admin.revenues.revenue.payment', compact('revenue', 'accounts', 'due'));
        }
        else{
            return redirect()->route('revenues.index')->with('error', 'Revenue Not Found');
        }
    }

    public function storePayment(Request $request, $revenue_id)
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

            $revenue = Revenue::find($revenue_id);

            $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount');

            $revenue_amount = floatval($revenue->amount);
            
            $due = $revenue_amount - $total_paid;

            if($request->amount > $due)
            {
                 return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be more than ' . $due]);
            }
            else if($request->amount < 1)
            {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than 1']);
            }

            $payment = new Payment();

            $payment->paymentable_type = 'App\Models\Revenue';

            $payment->paymentable_id = $revenue_id;

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
            
            $this->transaction_pool_service->tempStoreRevenuePayment($payment);

            DB::commit();

            return redirect()->route('revenues.index')->with('success', 'Payment Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('revenues.index')->with('error', $bug);
        }
    }

    public function editPayment(Request $request, $payment_id)
    {
        if(!in_array('revenue.payment.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.revenues.revenue.index');

        $payment = Payment::find($payment_id);

        $revenue = $payment->paymentable;

        $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount');

        $revenue_amount = floatval($revenue->amount);
        
        $due = $revenue_amount - $total_paid;

        $accounts = Account::whereHas('account_category', function ($query) {
            $query->where('name', '!=', 'Supplier Account');
            $query->where('name', '!=', 'Client Account');
        })
        ->where('warehouse_id', '=', $revenue->warehouse_id)
        ->get();

        return view('admin.revenues.revenue.edit_payment', compact('revenue','payment', 'accounts', 'due'));
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

            $revenue = $payment->paymentable;

            $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount') - $payment->amount;

            $revenue_amount = floatval($revenue->amount);
           
            $due = $revenue_amount - $total_paid;

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

            $revenue->payment_status = $total_paid == 0 ? 'Due' : 'Partial';

            $revenue->save();
            
            $this->transaction_pool_service->tempStoreRevenuePayment($payment);

            DB::commit();

            return redirect()->route('revenues.index')->with('success', 'Payment Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('revenues.index')->with('error', $bug);

        }

    }

    public function destroyPayment($id)
    {
        //
    }
}
