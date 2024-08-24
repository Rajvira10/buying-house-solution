<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\ProjectPhase;
use Illuminate\Http\Request;
use App\Models\ProjectStatus;
use App\Models\AccountCategory;
use App\Models\AccountStatement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TransactionPoolService;
use App\Services\AccountStatementService;

class ProjectController extends Controller
{
    private $transaction_pool_service, $accountStatementService;

    public function __construct(TransactionPoolService $transaction_pool_service, AccountStatementService $accountStatementService)
    {
        $this->transaction_pool_service = $transaction_pool_service;
        $this->accountStatementService = $accountStatementService;
    }
    
    public function index(Request $request)
    {
        if(!in_array('project.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.project.projects.index');
        
        if($request->ajax()){

            $projects = Project::orderBy('created_at', 'desc');

            return DataTables::of($projects)
                ->filter(function ($query) use ($request) {
                    if ($request['search']['value']) {
                        $query->where(function ($query) use ($request) {
                            $query->where('name', 'like', "%{$request['search']['value']}%");
                            $query->whereHas('client', function ($query) use ($request) {
                                $query->where('company_name', 'like', "%{$request['search']['value']}%");
                            });
                            $query->orWhereHas('project_type', function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request['search']['value']}%");
                            });
                            $query->orWhereHas('project_status', function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request['search']['value']}%");
                            });
                        })->get();
                    }
                })
                ->addColumn('client', function ($project) {
                    return $project->client->company_name;
                })
                ->addColumn('project_type', function ($project) {
                    return $project->project_type->name;
                })
                ->addColumn('project_status', function ($project) {
                    return $project->project_status->name;
                })
                ->addColumn('start_date', function ($project) {
                    return $project->start_date->format('d/m/Y') ?? '';
                })

                ->addColumn('end_date', function ($project) {
                    return $project->end_date != null ? $project->end_date->format('d/m/Y') : '';
                })
                
                ->addColumn('total_amount', function ($project) {
                    return number_format($project->project_phases->sum('amount'), 2, '.', ',');
                })

                ->addColumn('payment_status', function($project){

                    if($project->payment_status == 'Due')
                    {
                        return '<span class="badge bg-danger" style="font-size: 12px">Due</span>';
                    }
                    else if($project->payment_status == 'Partial')
                    {
                        return '<span class="badge bg-secondary" style="font-size: 12px">Partial</span';
                    }
                    else
                    {
                        return '<span class="badge bg-success" style="font-size: 12px">Paid</span>';
                    }

                })

                ->addColumn('total_paid', function($project){
                        
                        $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount');

                        return number_format($total_paid, 2, '.', ',');

                })

                ->addColumn('due', function($project){
                        
                    $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount');

                    $project_amount = floatval($project->project_phases->sum('amount'));
                    
                    $due = $project_amount - $total_paid;

                    return number_format($due, 2, '.', ',');

                })

                ->addColumn('finalized_by', function ($project) {
                    return $project->finalizedBy->first_name . ' ' . Carbon::parse($project->finalized_at)->format('d/m/Y h:i A');
                })

                ->addColumn('action', function ($project) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';                   
                    $edit_button .= '<li><a href="'.route('projects.show', $project->id).'" class
                    ="dropdown-item"><i class="ri-eye-fill me-2"></i> View</a></li>';
                    
                    if(in_array('project.payment.index', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'. route('projects.pending_payments', $project->id).'" class="dropdown-item edit-item-btn"><i class="ri-time-fill align-bottom me-2 text-warning"></i> Pending Payments</a></li>';
                        $edit_button .= '<li><a href="'. route('projects.approved_payments', $project->id).'" class="dropdown-item edit-item-btn"><i class="ri-eye-fill align-bottom me-2 text-success"></i> View Payments</a></li>';
                    }

                    if(in_array('project.payment.create', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <a href="'.route('projects.payment', $project->id).'" class="dropdown-item remove-item-btn">
                                                <i class="ri-currency-fill align-bottom me-2 text-info"></i> Make Payment
                                            </a>
                                        </li>';
                    }
                    if(in_array('project.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('projects.edit', $project->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('project.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteProject(' . $project->id . ')">
                                                <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                            </button>
                                        </li>';
                    }
                    
                    $edit_button .= '</ul></div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'payment_status'])
                ->make(true);
        }

        return view('admin.project.project.index');
    }

    public function create(Request $request)
    {
        if(!in_array('project.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.project.projects.index');

        $project_statuses = ProjectStatus::select('id', 'name')->get();
        $project_types = ProjectType::select('id', 'name')->get();
        $clients = Client::select('id', 'company_name')->get();

        $client_id = $request->client_id ?? null;

        return view('admin.project.project.create', compact( 'project_statuses', 'project_types', 'clients', 'client_id'));
    }
    
    public function show(Request $request, $project_id)
    {
        if(!in_array('project.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.project.projects.index');

        $project = Project::find($project_id);

        if($project != null){

            return view('admin.project.project.show', compact('project'));
        }
        else{
            return redirect()->route('projects.index')->with('error', 'Project Not Found');
        }
    }
    
    public function edit(Request $request, $project_id)
    {
        if(!in_array('project.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.project.projects.index');

        $project = Project::find($project_id);

        if($project != null){

            $project_statuses = ProjectStatus::select('id', 'name')->get();

            $project_types = ProjectType::select('id', 'name')->get();

            $clients = Client::select('id', 'company_name')->get();

            return view('admin.project.project.edit', compact('project', 'project_statuses', 'project_types', 'clients'));
        }
        else{
            return redirect()->route('projects.index')->with('error', 'Project Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'nullable|after_or_equal:start_date',
            'project_type_id' => 'required',
            'project_status_id' => 'required',
            'name' => 'required',
            'client_id' => 'required',
            'note' => 'nullable',
            'phases.*.name' => 'required|string|max:255',
            'phases.*.project_phase_status_id' => 'required|exists:project_statuses,id',
            'phases.*.start_date' => 'required',
            'phases.*.end_date' => 'nullable|after_or_equal:phases.*.start_date',
            'phases.*.amount' => 'required|numeric',
            'phases.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
        $project = new Project();

        $project->project_id = 'PROJECT';

        $project->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);

        if($request->end_date != null)
        {
            $project->end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
        }

        $project->warehouse_id = session('user_warehouse')->id;

        $project->project_type_id = $request->project_type_id;

        $project->project_status_id = $request->project_status_id;

        $project->name = $request->name;

        $project->client_id = $request->client_id;

        $project->note = $request->note;

        $project->payment_status = 'Due';

        $project->finalized_by = auth()->user()->id;

        $project->finalized_at = Carbon::now();

        $project->save();

        $project->project_id = 'PROJECT' . $project->id;

        $project->save();

        foreach($request->phases as $phase)
        {
            $project_phase = new ProjectPhase();

            $project_phase->project_id = $project->id;

            $project_phase->name = $phase['name'];

            $project_phase->project_phase_status_id = $phase['project_phase_status_id'];

            $project_phase->start_date = Carbon::createFromFormat('d/m/Y', $phase['start_date']);

            if($phase['end_date'] != null)
            {
                $project_phase->end_date = Carbon::createFromFormat('d/m/Y', $phase['end_date']);
            }

            $project_phase->amount = $phase['amount'];

            $project_phase->description = $phase['description'];

            $project_phase->save();
        }

        $client_account_category = AccountCategory::where('name', '=', 'Client Account')->first();

        if($client_account_category == null)
        {
            $client_account_category = new AccountCategory();

            $client_account_category->name = 'Client Account';

            $client_account_category->save();
        }

        $client_account = new Account();

        $client_account->name = $project->client->unique_id;

        $client_account->warehouse_id = session('user_warehouse')->id;
        
        $client_account->account_category_id = $client_account_category->id;

        $client_account->save();
        
        $this->accountStatementService->setInitialOpeningBalance($client_account->id, 0);
        
        DB::commit();

        return redirect()->route('projects.index')->with('success', 'Project Created Successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->route('projects.index')->with('error', 'Something went wrong');
        }
        
    }

    public function update(Request $request, $project_id)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'nullable',
            'project_type_id' => 'required',
            'project_status_id' => 'required',
            'name' => 'required',
            'client_id' => 'required',
            'note' => 'nullable',
            'phases.*.name' => 'required|string|max:255',
            'phases.*.project_phase_status_id' => 'required|exists:project_statuses,id',
            'phases.*.start_date' => 'required',
            'phases.*.end_date' => 'nullable',
            'phases.*.amount' => 'required|numeric',
            'phases.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $project = Project::find($project_id);

            $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount');

            if($request->phases != null)
            {
                $total_amount = 0;

                foreach($request->phases as $phase)
                {
                    $total_amount += $phase['amount'];
                }

                if($total_amount < $total_paid)
                {
                    return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than ' . $total_paid . ' as payment has been done']);
                }
            }

            $project->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);

            if($request->end_date != null)
            {
                $project->end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
            }

            $project->project_type_id = $request->project_type_id;

            $project->project_status_id = $request->project_status_id;

            $project->name = $request->name;

            $project->client_id = $request->client_id;

            $project->note = $request->note;

            $project_amount = $project->project_phases->sum('amount');

            if($total_paid == $project_amount)
            {
                $project->payment_status = 'Paid';
            }
            else if($total_paid > 0 && $total_paid < $project_amount)
            {
                $project->payment_status = 'Partial';
            }
            else{
                $project->payment_status = 'Due';
            }

            $project->save();

            $project->project_phases()->delete();

            foreach($request->phases as $phase)
            {
                $project_phase = new ProjectPhase();

                $project_phase->project_id = $project->id;

                $project_phase->name = $phase['name'];

                $project_phase->project_phase_status_id = $phase['project_phase_status_id'];

                $project_phase->start_date = Carbon::createFromFormat('d/m/Y', $phase['start_date']);

                if($phase['end_date'] != null)
                {
                    $project_phase->end_date = Carbon::createFromFormat('d/m/Y', $phase['end_date']);
                }

                $project_phase->amount = $phase['amount'];

                $project_phase->description = $phase['description'];

                $project_phase->save();
            }

            DB::commit();

            return redirect()->route('projects.index')->with('success', 'Project Updated Successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->route('projects.index')->with('error', 'Something went wrong');
        }
        
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            try {
            
                $project = Project::find($request->project_id);

                DB::beginTransaction();

                if($project != null)
                {
                    $transaction_pools = $project->payments()->with('transactionPool')->get()->pluck('transactionPool')->flatten()->unique('id');

                        $transaction_pools->each(function($item){
                            if($item != null)
                            {
                                $item->delete();
                            }
                        });

                        
                        $project->payments()->each(function($payment){
                            AccountStatement::where('reference_id', '=', $payment->id)
                                ->where('type', '=', 'Project')
                                ->delete();
                        });


                        $project->payments()->delete();

                        $project->delete();

                        DB::commit();
                        
                    return response()->json(['success' => 'Project Deleted Successfully']);
                }
                else{
                    return response()->json(['error' => 'Project Not Found']);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['error' => 'Something went wrong']);
            }
        }
    }

        public function pendingPayments(Request $request, $project_id)
    {
        if(!in_array('project.payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.project.projects.index');

        try{
            $project = Project::find($project_id);
            
            if($request->ajax()){

                $payments = Payment::where('paymentable_type', '=', 'App\Models\Project')
                ->where('paymentable_id', '=', $project->id)
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
            return view('admin.project.project.pending_payments', compact('project'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function approvedPayments(Request $request, $project_id)
    {
        if(!in_array('project.payment.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.project.projects.index');

        try{
            $project = Project::find($project_id);

            $payments = $project->payments()->where('status', '=', 'Approved')->get();

            $total_paid = $payments->sum('amount');

            $project_amount = floatval($project->project_phases->sum('amount'));
            
            $due = $project_amount - $total_paid;
            
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
                                            <li><a href="'. route('projects.edit_payment', $payment->id) .'" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        </ul>
                                    </div>';
                                    
                    return $edit_button;
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
            return view('admin.project.project.approved_payments', compact('project', 'due'));
        }
        catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function payment(Request $request, $project_id)
    {
        if(!in_array('project.payment.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.project.projects.index');

        $project = Project::find($project_id);

        if($project != null){

            $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount');

            $project_amount = floatval($project->project_phases->sum('amount'));
            
            $due = $project_amount - $total_paid;

            $accounts = Account::where('warehouse_id', '=', $project->warehouse_id)
            ->whereHas('account_category', function ($query) {
                $query->where('name', '!=', 'Supplier Account');
                $query->where('name', '!=', 'Client Account');
            })->get();


            return view('admin.project.project.payment', compact('project', 'accounts', 'due'));
        }
        else{
            return redirect()->route('projects.index')->with('error', 'Project Not Found');
        }
    }

    public function storePayment(Request $request, $project_id)
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

            $project = Project::find($project_id);

            $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount');

            $project_amount = floatval($project->project_phases->sum('amount'));
            
            $due = $project_amount - $total_paid;

            if($request->amount > $due)
            {
                 return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be more than ' . $due]);
            }
            else if($request->amount < 1)
            {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Amount can not be less than 1']);
            }

            $payment = new Payment();

            $payment->paymentable_type = 'App\Models\Project';

            $payment->paymentable_id = $project_id;

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
            
            $client_account_id = Account::where('name', '=', Client::find($project->client_id)->unique_id)->first()->id;

            $this->transaction_pool_service->tempStoreProjectPayment($payment, $client_account_id);

            DB::commit();

            return redirect()->route('projects.index')->with('success', 'Payment Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            dd($bug);
            return redirect()->route('projects.index')->with('error', $bug);
        }
    }

    public function editPayment(Request $request, $payment_id)
    {
        if(!in_array('project.payment.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.project.projects.index');

        $payment = Payment::find($payment_id);

        $project = $payment->paymentable;

        $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount');

        $project_amount = floatval($project->project_phases->sum('amount'));
        
        $due = $project_amount - $total_paid;

        $accounts = Account::whereHas('account_category', function ($query) {
            $query->where('name', '!=', 'Supplier Account');
            $query->where('name', '!=', 'Client Account');
        })
        ->where('warehouse_id', '=', $project->warehouse_id)
        ->get();

        return view('admin.project.project.edit_payment', compact('project','payment', 'accounts', 'due'));
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

            $project = $payment->paymentable;

            $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount') - $payment->amount;

            $project_amount = floatval($project->project_phases->sum('amount'));
           
            $due = $project_amount - $total_paid;

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

            $project->payment_status = $total_paid == 0 ? 'Due' : 'Partial';

            $project->save();
            
            $this->transaction_pool_service->tempStoreProjectPayment($payment);

            DB::commit();

            return redirect()->route('projects.index')->with('success', 'Payment Has Been Sent For Approval ');

        } catch (\Throwable $th) {
            DB::rollBack();
            $bug = $th->getMessage();
            return redirect()->route('projects.index')->with('error', $bug);

        }

    }

}
