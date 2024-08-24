<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AccountStatementService;

class SmsController extends Controller
{
    private $account_statement_service;

    public function __construct(AccountStatementService $account_statement_service)
    {
        $this->account_statement_service = $account_statement_service;
    }

    public function index(Request $request)
    {
        
    }

    public function create(Request $request)
    {
        $request->session()->now('view_name', 'admin.sms.index');

        $customers = Customer::with('account')->get(); 

        $due_customers = $customers->filter(function ($customer) {

            $account = $customer->account;

            $current_balance = $this->account_statement_service->getCurrentBalance($account->id);

            return $current_balance > 0;
        });

        return view('admin.sms.create', compact('due_customers'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'message' => 'required',
        ]);

        $customer = Customer::find($request->customer_id);

        $account = $customer->account;

        $current_balance = $this->account_statement_service->getCurrentBalance($account->id);

        $message = $request->message;

        $message = str_replace('{customer_name}', $customer->name, $message);
        $message = str_replace('{customer_mobile}', $customer->mobile, $message);
        $message = str_replace('{current_balance}', $current_balance, $message);

        $this->sendSms($customer->mobile, $message);

        $request->session()->flash('success', 'SMS sent successfully');

        return redirect()->route('admin.sms.create');
    }

    
}
