<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PayrollDetail;
use App\Models\ExpenseCategory;
use App\Models\SalaryStructure;
use App\Models\AccountStatement;
use App\Services\PayrollService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{
    private $payroll_service;

    public function __construct(PayrollService $payroll_service)
    {
        $this->payroll_service = $payroll_service;    
    }

    public function index(Request $request)
    {
        if(!in_array('salary_structure.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.salary_sheet.index');

        $warehouse = session('user_warehouse');

        try {
            if($request->ajax()){
                
                $payrolls = Payroll::join('warehouses', 'payrolls.warehouse_id', '=', 'warehouses.id')
                ->select(
                    'payrolls.*',
                    'warehouses.name as warehouse'
                )
                ->where('warehouse_id', '=', $warehouse->id)
                ->orderBy('month', 'desc')
                ->get();

                
                return DataTables::of($payrolls)
                    
                    ->addColumn('finalized_by', function($payroll){
                        
                        $user = User::where('id', '=', $payroll->finalized_by)
                        ->select(
                            'username'
                        )
                        ->first();

                        return $user->username . ' ' . Carbon::parse($payroll->finalized_at)->format('d/m/Y h:i A');
                                                 
                    })

                    ->addColumn('month', function($payroll){
                        return Carbon::parse($payroll->month)->format('F Y');
                    })

                    ->addColumn('action', function ($payroll) {
                        
                        $edit_button = '';
                        
                        if(!in_array('salary_sheet.payroll_details', session('user_permissions')))
                        {
                            $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="'. route('payrolls.show', $payroll->id).'" class="dropdown-item edit-item-btn"><i class=" ri-eye-fill align-bottom me-2 text-primary"></i> View</a>
                                                </li>
                                            </ul>
                                        </div>';
                        }
                        else{
                            $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="'. route('payrolls.show', $payroll->id).'" class="dropdown-item edit-item-btn"><i class=" ri-eye-fill align-bottom me-2 text-primary"></i> View</a>
                                                    <a href="'. route('payrolls.payroll_details', $payroll->id).'" class="dropdown-item edit-item-btn"><i class=" ri-file-text-fill align-bottom me-2 text-primary"></i> Payroll Details</a>
                                                </li>
                                            </ul>
                                        </div>';
                        }
                        

                        return $edit_button;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
                }

            return view('admin.hrm.payroll.index');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('payrolls.index')->with('error', 'Something Went Wrong');
        }
    }

    public function create(Request $request)
    {
        if(!in_array('salary_sheet.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.payroll.index');

        return view('admin.hrm.payroll.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required',
        ]);


        DB::beginTransaction();

        try {

            $payroll = Payroll::where('month', '=', Carbon::parse($request->month)->format('Y-m-d'))
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->first();

            if($payroll){
                return redirect()->route('payrolls.index')->with('error', 'Payroll Already Exists For This Month and Warehouse');
            }

            $payroll = new Payroll();

            $payroll->warehouse_id = session('user_warehouse')->id;

            $payroll->month = Carbon::parse($request->month)->format('Y-m-d');

            $payroll->finalized_by = auth()->user()->id;

            $payroll->finalized_at = Carbon::now();

            $payroll->save();

            $payroll->payroll_no = 'PAY-' . ($payroll->id+1000);
        
            $payroll->save();

            $employees = Employee::where('status', '=', 'Active')
                ->where('warehouse_id', '=', session('user_warehouse')->id)
                ->select('id')
                ->get();

            foreach ($employees as $employee) {

                $payroll_details = new PayrollDetail();
                
                $payroll_details->payroll_id = $payroll->id;

                $payroll_details->employee_id = $employee->id;

                $payroll_details->days_absent = 0;

                $payroll_details->payment_status = 'Due';

                $payroll_details->save();

            }

            DB::commit();

            return redirect()->route('payrolls.index')->with('success', 'Payroll Created Successfully');

        } catch (\Throwable $th) {

            DB::rollback();

            return redirect()->route('payrolls.index')->with('error', $th->getMessage());
        }
    }

    public function show(Request $request, $payroll_id)
    {
        if(!in_array('salary_structure.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.salary_sheet.index');

        $payroll = Payroll::find($payroll_id);
       
        $month = Carbon::parse($payroll->month)->format('F Y');

        $daysInMonth = Carbon::parse($payroll->month)->daysInMonth;

        $payroll_details = PayrollDetail::where('payroll_id', $payroll_id)->with('employee')->get();

        $employeeIds = $payroll_details->pluck('employee_id');
        
        $salary_structures = SalaryStructure::whereIn('employee_id', $employeeIds)
            ->get()
            ->keyBy('employee_id');


        foreach ($payroll_details as $detail) {
            
            $employeeId = $detail->employee_id;
            
            [$gross_salary, $basic, $bonus, $h_rent, $med, $conveyance] = $this->payroll_service->calculateGrossSalary($detail);

            $detail->basic = $basic;
            
            $detail->h_rent = $h_rent; 

            $detail->med = $med;
           
            $detail->conv = $conveyance;

            $detail->gross_salary = $gross_salary;

            [$deduction, $net_salary] = $this->payroll_service->calculateNetSalary($detail);

            $detail->net_salary = $net_salary;
        }

        return view('admin.hrm.payroll.show', compact('payroll', 'payroll_details', 'month', 'daysInMonth'));
    }


    public function payrollDetails(Request $request, $payroll_id)
    {
        if(!in_array('salary_sheet.payroll_details', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.salary_sheet.index');

        $payroll = Payroll::find($payroll_id);
       
        $month = Carbon::parse($payroll->month)->format('F Y');

        $daysInMonth = Carbon::parse($payroll->month)->daysInMonth;

        $payroll_details = PayrollDetail::where('payroll_id', $payroll_id)->with('employee')->get();

        $employeeIds = $payroll_details->pluck('employee_id');
        
        $salary_structures = SalaryStructure::whereIn('employee_id', $employeeIds)
            ->get()
            ->keyBy('employee_id');

        $accounts = Account::where('warehouse_id', '=', session('user_warehouse')->id)
            ->whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account');
                $query->where('name', '!=', 'Client Account');
            })->get();

        foreach ($payroll_details as $detail) {
            
            $employeeId = $detail->employee_id;
            
            $detail->gross = $salary_structures[$employeeId]->gross;
            
            $detail->h_rent = $salary_structures[$employeeId]->h_rent_percent * $detail->gross / 100; 

            $detail->med = $salary_structures[$employeeId]->med_percent * $detail->gross / 100;
           
            $detail->conv = $salary_structures[$employeeId]->conv_percent * $detail->gross / 100;
        }

        return view('admin.hrm.payroll.payroll_details', compact('payroll', 'payroll_details', 'month', 'daysInMonth', 'accounts'));
    }

    public function disburse(Request $request, $payroll_detail_id)
    {
        // if(!in_array('payroll.disburse', session('user_permissions')))
        // {
        //     return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        // }

        $request->validate(
            [
                'payment_date' => 'required',
            ]
        );

        DB::beginTransaction();

        try {
            $payroll_detail = PayrollDetail::find($payroll_detail_id);

            $payroll_detail->bonus = $request->bonus;

            $payroll_detail->days_absent = $request->days_absent ?? 0;

            $payroll_detail->bonus = $request->bonus ?? 0;

            $payroll_detail->payment_status = 'Paid';

            $payroll_detail->payment_method = Account::find($request->account_id)->name;

            $payroll_detail->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->toDateTimeString();

            $payroll_detail->disbursed_by = auth()->user()->id;

            $payroll_detail->disbursed_at = Carbon::now();

            $payroll_detail->save();

            $expense_category = ExpenseCategory::where('name', '=', 'Employee Salary')
                ->first();
            
            if($expense_category == null)
            {
                $expense_category = new ExpenseCategory();

                $expense_category->name = 'Employee Salary';

                $expense_category->save();
            }

            $expense = new Expense();

            $expense->expense_category_id = $expense_category->id;

            $expense->warehouse_id = session('user_warehouse')->id;

            $expense->employee_id = $payroll_detail->employee_id;

            [$deduction, $net_salary] = $this->payroll_service->calculateNetSalary($payroll_detail);

            $expense->amount = $net_salary;

            $expense->payment_status = 'Paid';

            $expense->date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->toDateTimeString();

            $expense->note = 'Employee Salary';

            $expense->finalized_by = auth()->user()->id;

            $expense->finalized_at = Carbon::now();

            $expense->save();

            $expense->expense_no = 'EXP-' . ($expense->id+1000);

            $expense->save();

            $payment = new Payment();

            $payment->paymentable_id = $expense->id;

            $payment->paymentable_type = 'App\Models\Expense';

            $payment->amount = $net_salary;

            $payment->date = Carbon::createFromFormat('d/m/Y', $request->payment_date)->toDateTimeString();

            $payment->account_id = $request->account_id;

            $payment->finalized_by = auth()->user()->id;

            $payment->finalized_at = Carbon::now();

            $payment->note = 'Employee Salary';

            $payment->status = 'Approved';

            $payment->save();

            $payment->payment_no = 'PAY-' . ($payment->id+1000);

            $payment->save();

            $account_statement = new AccountStatement();

            $account_statement->type = 'Expense';

            $account_statement->reference_id = $payment->id;

            $account_statement->account_id = $payment->account_id;

            $account_statement->amount = - $payment->amount;

            $account_statement->cash_flow_type = 'Debit';

            $account_statement->statement_date = Carbon::now();

            $account_statement->save();

            DB::commit();

            return redirect()->back()->with('success', 'Salary Disbursed Successfully');

        } catch (\Throwable $th) {

            DB::rollback();

            return redirect()->route('payrolls.index')->with('error', $th->getMessage());
        }
    }

    public function payrollIndex(Request $request)
    {
        if(!in_array('payroll.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.hrm.payroll.index');

        $employees = Employee::where('warehouse_id', '=', session('user_warehouse')->id)
            ->get();

        try {
            if($request->ajax()){

                $employee_id = $request->input('employee_id');

                $payroll_details = PayrollDetail::where('employee_id', '=', $employee_id)
                ->where('payment_status', '=', 'Paid')
                ->where('payment_date', '<=', Carbon::tomorrow()->format('Y-m-d'))
                ->latest()
                ->get();       
                
                foreach ($payroll_details as $detail) {

                    [$deduction, $net_salary] = $this->payroll_service->calculateNetSalary($detail);

                    $detail->net_salary = $net_salary;
                }

                return DataTables::of($payroll_details)            

                    ->addColumn('payroll_no', function($detail){
                        return $detail->payroll->payroll_no;
                    })

                    ->addColumn('payment_date', function($detail){
                        return Carbon::parse($detail->payment_date)->format('d/m/Y');
                    })

                    ->addColumn('month', function($detail){
                        return Carbon::parse($detail->payroll->month)->format('F Y');
                    })
                    
                    ->addColumn('net_salary', function($detail){
                        return number_format($detail->net_salary, 2);
                    })    

                    ->addColumn('disbursed_by', function($detail){
                        
                        $user = User::where('id', '=', $detail->disbursed_by)
                        ->select(
                            'username'
                        )
                        ->first();

                        return $user->username . ' ' . Carbon::parse($detail->disbursed_at)->format('d/m/Y h:i A');
                                                 
                    })

                    ->addColumn('printed_by', function($detail){

                        if($detail->printed_by){
                            $user = User::where('id', '=', $detail->printed_by)
                                ->select(
                                    'username'
                                )
                                ->first();

                            return $user->username . ' ' . Carbon::parse($detail->printed_at)->format('d/m/Y h:i A');
                        }
                        else{
                            return '<div class="badge bg-danger">Not Printed</div>';
                        }
                                                 
                    })
                    ->addColumn('action', function ($detail) {
                        
                        $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="'.route('payrolls.payroll.print', $detail->id).'" class="dropdown-item edit-item-btn"><i class="ri-printer-fill align-bottom me-2 text-primary" style="font-size: 16px"></i> Print</a>
                                                </li>
                                            </ul>
                                        </div>';

                        return $edit_button;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'printed_by'])
                    ->make(true);
                }

            return view('admin.hrm.payroll.payrollIndex', compact('employees'));
        } catch (\Throwable $th) {
            return redirect()->route('payrolls.index')->with('error', 'Something Went Wrong');
        }
    }

    public function payrollPrint(Request $request, $payroll_id)
    {
        $payroll_detail = PayrollDetail::find($payroll_id);

        $payroll_detail->printed_by = auth()->user()->id;

        $payroll_detail->printed_at = Carbon::now();

        $payroll_detail->save();

        [$gross_salary, $basic, $bonus, $h_rent, $med, $conveyance] = $this->payroll_service->calculateGrossSalary($payroll_detail);

        $payroll_detail->basic = $basic;

        $payroll_detail->h_rent = $h_rent;

        $payroll_detail->med = $med;

        $payroll_detail->conv = $conveyance;

        $payroll_detail->gross_salary = $gross_salary;

        [$deduction, $net_salary] = $this->payroll_service->calculateNetSalary($payroll_detail);
        
        $payroll_detail->deduction = $deduction;

        $payroll_detail->net_salary = $net_salary;

        return view('admin.hrm.payroll.payrollPrint', compact('payroll_detail'));
    }
    
}
