<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Sale;
use App\Models\User;
use App\Models\Client;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Revenue;
use App\Models\Customer;
use App\Models\Investor;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Wholesale;
use App\Models\Investment;
use App\Models\LoanClient;
use App\Models\SaleReturn;
use App\Models\LoanPayback;
use App\Models\MoneyTransfer;
use App\Models\PurchaseReturn;
use App\Models\ExpenseCategory;
use App\Models\RevenueCategory;
use App\Models\TransactionPool;
use App\Models\WholesaleReturn;
use App\Models\InvestmentReturn;
use App\Services\AccountStatementService;

class TransactionPoolService 
{
    private $account_statement_service;

    public function __construct(AccountStatementService $account_statement_service)
    {
        $this->account_statement_service = $account_statement_service;
    }

    public function tempStorePayment($request, $payment)
    {

        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';

        $transaction_pool->poolable_id = $payment->id;
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y');

        $payment_presentation_data['Transaction Type'] = $request->transaction_type == 'Payable' ? 'Paid' : 'Received'; 

        if($payment->amount < 0){
            $payment_presentation_data['Amount'] = $payment->amount * (-1);
        }
        else{
            $payment_presentation_data['Amount'] = $payment->amount;
        }

        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        if($request->supplier_id != null){
            $payment_presentation_data['Supplier'] = Supplier::find($request->supplier_id)->name;
        }
        else if($request->customer_id != null){
            $payment_presentation_data['Customer'] = Customer::find($request->customer_id)->name;
        }

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Payment';

        $payment_data['contact_type'] = $request->supplier_customer;

        $payment_data['contact_id'] = $request->supplier_id != null ? $request->supplier_id : $request->customer_id;

        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storePayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
    }

    public function storePayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $contact_type = json_decode($transaction_pool->data)->contact_type;

        $contact_id = json_decode($transaction_pool->data)->contact_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR PAYMENT

        if($contact_type == 'Customer'){

            $account_statement_arr = [
                'type' => 'Payment',
                'reference_id' => $payment->id,
                'amount' =>  $payment->amount,
                'account_id' => $payment->account_id,
                'cash_flow_type' => $payment->amount < 0 ? 'Debit' : 'Credit',
                'customer_account_id' => Account::where('name', '=', Customer::find($contact_id)->unique_id)->first()->id,        
            ];
        }
        else if($contact_type == 'Supplier'){

            $account_statement_arr = [
                'type' => 'Payment',
                'reference_id' => $payment->id,
                'amount' =>  $payment->amount,
                'account_id' => $payment->account_id,
                'cash_flow_type' => $payment->amount < 0 ? 'Debit' : 'Credit',
                'supplier_account_id' => Account::where('name', '=', Supplier::find($contact_id)->unique_id)->first()->id,        
            ];
        }


        return $account_statement_arr;
    }

    public function tempStoreExpense($request)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Expense';
        
        $transaction_pool->action_type = 'Create';


        $expense['payment_status'] = 'Due';

        $expense['finalized_by'] = auth()->user()->id;
        
        $expense['finalized_at'] = Carbon::now()->toDateTimeString();

        $expense_official_data = array_merge($request->except('_token'), $expense);


        // FOR PRESENTATION PURPOSE ONLY

        $expense_presentation_data['Warehouse'] = Warehouse::find($request->warehouse_id)->name;

        $expense_presentation_data['Expense Category'] = ExpenseCategory::find($request->expense_category_id)->name;

        $expense_presentation_data['Date'] = $request->date;

        $expense_presentation_data['Amount'] = $request->amount;

        $expense_presentation_data['Note'] = $request->note;

        $expense_presentation_data['Payment Status'] = $expense['payment_status'];

        $expense_presentation_data['Finalized By'] = User::find($expense['finalized_by'])->username;

        $expense_presentation_data['Finalized At'] = Carbon::parse($expense['finalized_at'])->format('d/m/Y h:i A');



        $expense_data['official_data'] = $expense_official_data;

        $expense_data['presentation_data'] = $expense_presentation_data;

        
        $transaction_pool->data = json_encode($expense_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $this->storeExpense($transaction_pool);

            $transaction_pool->delete();

        }
    }

    public function storeExpense($transaction_pool)
    {
        $expense = new Expense();

        $expense_obj = json_decode($transaction_pool->data)->official_data;

        $expense->expense_category_id = $expense_obj->expense_category_id;

        $expense->warehouse_id = $expense_obj->warehouse_id;

        $expense->amount = $expense_obj->amount;

        $expense->payment_status = $expense_obj->payment_status;

        $expense->date = Carbon::createFromFormat('d/m/Y', $expense_obj->date)->toDateTimeString();

        $expense->note = $expense_obj->note;

        $expense->finalized_by = $expense_obj->finalized_by;

        $expense->finalized_at = $expense_obj->finalized_at;

        $expense->save();


        $expense->expense_no = "Exp-" . ($expense->id + 1000);

        $expense->save();


        $expense->transactionPool()->save($transaction_pool);
    }

    public function tempStoreExpensePayment($payment)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Expense No'] = Expense::find($payment->paymentable_id)->expense_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;
        
        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Expense';
 
        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeExpensePayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storeExpensePayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $expense = $payment->paymentable;

        $total_paid = $expense->payments()->where('status', '=', 'Approved')->sum('amount');

        if ($total_paid < $expense->amount)
        {
            $expense->payment_status = 'Partial';
        } 
        else if($total_paid == $expense->amount)
        {
            $expense->payment_status = 'Paid';
        }

        $expense->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR EXPENSE PAYMENT

        $account_statement_arr = [
            'type' => 'Expense',
            'reference_id' => $payment->id,
            'amount' => $payment->amount * (-1),
            'account_id' => $payment->account_id,
            'cash_flow_type' => 'Debit',
        ];

        return $account_statement_arr;
    }
    


    public function tempStoreRevenue($request)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Revenue';
        
        $transaction_pool->action_type = 'Create';


        $revenue['payment_status'] = 'Due';

        $revenue['finalized_by'] = auth()->user()->id;
        
        $revenue['finalized_at'] = Carbon::now()->toDateTimeString();

        $revenue_official_data = array_merge($request->except('_token'), $revenue);


        // FOR PRESENTATION PURPOSE ONLY
        $revenue_presentation_data['Warehouse'] = Warehouse::find($request->warehouse_id)->name;

        $revenue_presentation_data['Revenue Category'] = RevenueCategory::find($request->revenue_category_id)->name;

        $revenue_presentation_data['Date'] = $request->date;

        $revenue_presentation_data['Amount'] = $request->amount;

        $revenue_presentation_data['Note'] = $request->note;

        $revenue_presentation_data['Payment Status'] = $revenue['payment_status'];

        $revenue_presentation_data['Finalized By'] = User::find($revenue['finalized_by'])->username;

        $revenue_presentation_data['Finalized At'] = Carbon::parse($revenue['finalized_at'])->format('d/m/Y h:i A');



        $revenue_data['official_data'] = $revenue_official_data;

        $revenue_data['presentation_data'] = $revenue_presentation_data;

        
        $transaction_pool->data = json_encode($revenue_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $this->storeRevenue($transaction_pool);

            $transaction_pool->delete();

        }
    }

    public function storeRevenue($transaction_pool)
    {
        $revenue = new Revenue();

        $revenue_obj = json_decode($transaction_pool->data)->official_data;

        $revenue->warehouse_id = $revenue_obj->warehouse_id;

        $revenue->revenue_category_id = $revenue_obj->revenue_category_id;

        $revenue->amount = $revenue_obj->amount;

        $revenue->payment_status = $revenue_obj->payment_status;

        $revenue->date = Carbon::createFromFormat('d/m/Y', $revenue_obj->date)->toDateTimeString();

        $revenue->note = $revenue_obj->note;

        $revenue->finalized_by = $revenue_obj->finalized_by;

        $revenue->finalized_at = $revenue_obj->finalized_at;

        $revenue->save();


        $revenue->revenue_no = "Rev-" . ($revenue->id + 1000);

        $revenue->save();


        $revenue->transactionPool()->save($transaction_pool);
    }

    public function tempStoreRevenuePayment($payment)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Revenue No'] = Revenue::find($payment->paymentable_id)->revenue_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;
        
        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Revenue';
 
        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeRevenuePayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storeRevenuePayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $revenue = $payment->paymentable;

        $total_paid = $revenue->payments()->where('status', '=', 'Approved')->sum('amount');

        if ($total_paid < $revenue->amount)
        {
            $revenue->payment_status = 'Partial';
        } 
        else if($total_paid == $revenue->amount)
        {
            $revenue->payment_status = 'Paid';
        }

        $revenue->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR REVENUE PAYMENT

        $account_statement_arr = [
            'type' => 'Revenue',
            'reference_id' => $payment->id,
            'amount' => $payment->amount,
            'account_id' => $payment->account_id,
            'cash_flow_type' => 'Credit',
        ];

        return $account_statement_arr;
    }

    public function tempStoreMoneyTransfer($request, $warehouse_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\MoneyTransfer';
        
        $transaction_pool->action_type = 'Create';

        $money_transfer['finalized_by'] = auth()->user()->id;
        
        $money_transfer['finalized_at'] = Carbon::now()->toDateTimeString();

        $money_transfer_official_data = array_merge($request->except('_token'), $money_transfer);

        $money_transfer_official_data['warehouse_id'] = $warehouse_id;

        // FOR PRESENTATION PURPOSE ONLY
        $money_transfer_presentation_data['Date'] = $request->date;

        $money_transfer_presentation_data['Amount'] = $request->amount;

        $money_transfer_presentation_data['Sender Account'] = Account::find($request->sender_account_id)->name;

        $money_transfer_presentation_data['Receiver Account'] = Account::find($request->receiver_account_id)->name;

        $money_transfer_presentation_data['Note'] = $request->note;

        $money_transfer_presentation_data['Finalized By'] = User::find($money_transfer['finalized_by'])->username;

        $money_transfer_presentation_data['Finalized At'] = Carbon::parse($money_transfer['finalized_at'])->format('d/m/Y h:i A');


        $money_transfer_data['official_data'] = $money_transfer_official_data;

        $money_transfer_data['presentation_data'] = $money_transfer_presentation_data;

        
        $transaction_pool->data = json_encode($money_transfer_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            [$sender_account_statement_arr, $receiver_account_statement_arr] = $this->storeMoneyTransfer($transaction_pool);

            $this->account_statement_service->store($sender_account_statement_arr);

            $this->account_statement_service->store($receiver_account_statement_arr);

            $transaction_pool->delete();

        }
    }

    public function tempUpdateMoneyTransfer($request, $transaction_pool_id)
    {
        $transaction_pool = TransactionPool::find($transaction_pool_id);

        $money_transfer['finalized_by'] = auth()->user()->id;

        $money_transfer['finalized_at'] = Carbon::now()->toDateTimeString();

        $money_transfer_official_data = array_merge($request->except('_token'), $money_transfer);


        // FOR PRESENTATION PURPOSE ONLY

        $money_transfer_presentation_data['Date'] = $request->date;

        $money_transfer_presentation_data['Amount'] = $request->amount;

        $money_transfer_presentation_data['Sender Account'] = Account::find($request->sender_account_id)->name;

        $money_transfer_presentation_data['Receiver Account'] = Account::find($request->receiver_account_id)->name;

        $money_transfer_presentation_data['Note'] = $request->note;

        $money_transfer_presentation_data['Finalized By'] = User::find($money_transfer['finalized_by'])->username;

        $money_transfer_presentation_data['Finalized At'] = Carbon::parse($money_transfer['finalized_at'])->format('d/m/Y h:i A');


        $money_transfer_data['official_data'] = $money_transfer_official_data;

        $money_transfer_data['presentation_data'] = $money_transfer_presentation_data;


        $transaction_pool->data = json_encode($money_transfer_data);

        $transaction_pool->save();

    }

    public function storeMoneyTransfer($transaction_pool)
    {
        $money_transfer = new MoneyTransfer();

        $money_transfer_obj = json_decode($transaction_pool->data)->official_data;

        $money_transfer->warehouse_id = $money_transfer_obj->warehouse_id;

        $money_transfer->amount = $money_transfer_obj->amount;

        $money_transfer->sender_account_id = $money_transfer_obj->sender_account_id;

        $money_transfer->receiver_account_id = $money_transfer_obj->receiver_account_id;

        $money_transfer->date = Carbon::createFromFormat('d/m/Y', $money_transfer_obj->date)->toDateTimeString();

        $money_transfer->note = $money_transfer_obj->note;

        $money_transfer->finalized_by = $money_transfer_obj->finalized_by;

        $money_transfer->finalized_at = $money_transfer_obj->finalized_at;

        $money_transfer->save();


        $money_transfer->transfer_no = "Tfr-" . ($money_transfer->id + 1000);

        $money_transfer->save();


        $money_transfer->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR MONEY TRANSFER

        $sender_account_statement_arr = [
            'type' => 'Money Transfer',
            'reference_id' => $money_transfer->id,
            'amount' => $money_transfer->amount * (-1),
            'account_id' => $money_transfer->sender_account_id,
            'cash_flow_type' => 'Debit',
        ];

        $receiver_account_statement_arr = [
            'type' => 'Money Transfer',
            'reference_id' => $money_transfer->id,
            'amount' => $money_transfer->amount,
            'account_id' => $money_transfer->receiver_account_id,
            'cash_flow_type' => 'Credit',
        ];

        return [$sender_account_statement_arr, $receiver_account_statement_arr];
    }


    public function tempStoreLoan($request, $warehouse_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Loan';
        
        $transaction_pool->action_type = 'Create';

        $loan['payback_status'] = 'Due';

        $loan['finalized_by'] = auth()->user()->id;
        
        $loan['finalized_at'] = Carbon::now()->toDateTimeString();

        $loan_official_data = array_merge($request->except('_token'), $loan);

        $loan_official_data['warehouse_id'] = $warehouse_id;

        // FOR PRESENTATION PURPOSE ONLY
        $loan_presentation_data['Loan Client'] = LoanClient::find($request->loan_client_id)->name;

        $loan_presentation_data['Date'] = $request->date;

        $loan_presentation_data['Type'] = $request->type;

        $loan_presentation_data['Amount'] = $request->amount;

        $loan_presentation_data['Account'] = Account::find($request->account_id)->name;

        $loan_presentation_data['Note'] = $request->note;

        $loan_presentation_data['Payback Status'] = $loan['payback_status'];

        $loan_presentation_data['Finalized By'] = User::find($loan['finalized_by'])->username;

        $loan_presentation_data['Finalized At'] = Carbon::parse($loan['finalized_at'])->format('d/m/Y h:i A');


        $loan_data['official_data'] = $loan_official_data;

        $loan_data['presentation_data'] = $loan_presentation_data;

        
        $transaction_pool->data = json_encode($loan_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
        $account_statement_arr = $this->storeLoan($transaction_pool);

        $this->account_statement_service->store($account_statement_arr);
            
        $transaction_pool->delete();

        }
    }

    public function storeLoan($transaction_pool)
    {
        $loan = new Loan();

        $loan_obj = json_decode($transaction_pool->data)->official_data;

        $loan->warehouse_id = $loan_obj->warehouse_id;

        $loan->loan_client_id = $loan_obj->loan_client_id;

        $loan->amount = $loan_obj->amount;

        $loan->type = $loan_obj->type;

        $loan->date = Carbon::createFromFormat('d/m/Y', $loan_obj->date)->toDateTimeString();

        $loan->account_id = $loan_obj->account_id;

        $loan->note = $loan_obj->note;

        $loan->payback_status = $loan_obj->payback_status;

        $loan->finalized_by = $loan_obj->finalized_by;

        $loan->finalized_at = $loan_obj->finalized_at;

        $loan->save();

        $loan->loan_no = "Ln-" . ($loan->id + 1000);

        $loan->save();

        $loan->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR LOAN

        if($loan->type == 'Receivable')
        {
            $amount = $loan->amount;
            $cash_flow_type = 'Credit';
        }
        else if($loan->type == 'Payable')
        {
            $amount = $loan->amount * (-1);
            $cash_flow_type = 'Debit';
        }

        $account_statement_arr = [
            'type' => 'Loan',
            'reference_id' => $loan->id,
            'amount' => $amount,
            'account_id' => $loan->account_id,
            'cash_flow_type' => $cash_flow_type,
        ];

        return $account_statement_arr;
    }

    public function tempStoreLoanPayback($loan_payback)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\LoanPayback';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $loan_payback_presentation_data['Loan Payback No'] = $loan_payback->payback_no;

        $loan_payback_presentation_data['Loan No'] = Loan::find($loan_payback->loan_id)->loan_no;

        $loan_payback_presentation_data['Date'] = Carbon::parse($loan_payback->date)->format('d/m/Y') ;

        $loan_payback_presentation_data['Amount'] = $loan_payback->amount;
        
        $loan_payback_presentation_data['Account'] = Account::find($loan_payback->account_id)->name;

        $loan_payback_presentation_data['Note'] = $loan_payback->note;

        $loan_payback_presentation_data['Finalized By'] = User::find($loan_payback->finalized_by)->username;

        $loan_payback_presentation_data['Finalized At'] = Carbon::parse($loan_payback->finalized_at)->format('d/m/Y h:i A');


        $loan_payback_data['presentation_data'] = $loan_payback_presentation_data;

        $loan_payback_data['loan_payback_id'] = $loan_payback->id;
 
        $transaction_pool->data = json_encode($loan_payback_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeLoanPayback($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storeLoanPayback($transaction_pool)
    {
        $loan_payback_id = json_decode($transaction_pool->data)->loan_payback_id;

        $loan_payback = LoanPayback::find($loan_payback_id);

        $loan_payback->status = 'Approved';

        $loan_payback->save();


        $loan = $loan_payback->loan;

        $total_paid = $loan->paybacks()->where('status', '=', 'Approved')->sum('amount');

        if ($total_paid < $loan->amount)
        {
            $loan->payback_status = 'Partial';
        } 
        else if($total_paid == $loan->amount)
        {
            $loan->payback_status = 'Paid';
        }

        $loan->save();

        $loan_payback->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR LOAN PAYBACK PAYMENT

        if($loan->type == 'Receivable')
        {
            $amount = $loan_payback->amount * (-1);
            $cash_flow_type = 'Debit';
        }
        else if($loan->type == 'Payable')
        {
            $amount = $loan_payback->amount;
            $cash_flow_type = 'Credit';
        }

        $account_statement_arr = [
            'type' => 'Loan Payback',
            'reference_id' => $loan_payback->id,
            'amount' => $amount,
            'account_id' => $loan_payback->account_id,
            'cash_flow_type' => $cash_flow_type,
        ];

        return $account_statement_arr;
    }

    public function tempStoreInvestment($request, $warehouse_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Investment';
        
        $transaction_pool->action_type = 'Create';

        $investment['return_status'] = 'Due';

        $investment['finalized_by'] = auth()->user()->id;
        
        $investment['finalized_at'] = Carbon::now()->toDateTimeString();

        $investment_official_data = array_merge($request->except('_token'), $investment);

        $investment_official_data['warehouse_id'] = $warehouse_id;


        // FOR PRESENTATION PURPOSE ONLY
        $investment_presentation_data['Investor'] = Investor::find($request->investor_id)->name;

        $investment_presentation_data['Date'] = $request->date;

        $investment_presentation_data['Type'] = $request->type;

        $investment_presentation_data['Amount'] = $request->amount;

        $investment_presentation_data['Account'] = Account::find($request->account_id)->name;

        $investment_presentation_data['Note'] = $request->note;

        $investment_presentation_data['Return Status'] = $investment['return_status'];

        $investment_presentation_data['Finalized By'] = User::find($investment['finalized_by'])->username;

        $investment_presentation_data['Finalized At'] = Carbon::parse($investment['finalized_at'])->format('d/m/Y h:i A');


        $investment_data['official_data'] = $investment_official_data;

        $investment_data['presentation_data'] = $investment_presentation_data;

        
        $transaction_pool->data = json_encode($investment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
        $account_statement_arr = $this->storeInvestment($transaction_pool);

        $this->account_statement_service->store($account_statement_arr);

        $transaction_pool->delete();

        }
    }

    public function storeInvestment($transaction_pool)
    {
        $investment = new Investment();

        $investment_obj = json_decode($transaction_pool->data)->official_data;

        $investment->warehouse_id = $investment_obj->warehouse_id;

        $investment->investor_id = $investment_obj->investor_id;

        $investment->amount = $investment_obj->amount;

        $investment->type = $investment_obj->type;

        $investment->date = Carbon::createFromFormat('d/m/Y', $investment_obj->date)->toDateTimeString();

        $investment->account_id = $investment_obj->account_id;

        $investment->note = $investment_obj->note;

        $investment->return_status = $investment_obj->return_status;

        $investment->finalized_by = $investment_obj->finalized_by;

        $investment->finalized_at = $investment_obj->finalized_at;

        $investment->save();

        $investment->investment_no = "Invt-" . ($investment->id + 1000);

        $investment->save();

        $investment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR INVESTMENT

        if($investment->type == 'Receivable')
        {
            $amount = $investment->amount;
            $cash_flow_type = 'Credit';
        }
        else if($investment->type == 'Payable')
        {
            $amount = $investment->amount * (-1);
            $cash_flow_type = 'Debit';
        }

        $account_statement_arr = [
            'type' => 'Investment',
            'reference_id' => $investment->id,
            'amount' => $amount,
            'account_id' => $investment->account_id,
            'cash_flow_type' => $cash_flow_type,
        ];

        return $account_statement_arr;
    }

    public function tempStoreInvestmentReturn($investment_return)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\InvestmentReturn';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $investment_return_presentation_data['Investment Return No'] = $investment_return->return_no;

        $investment_return_presentation_data['Investment No'] = Investment::find($investment_return->investment_id)->investment_no;

        $investment_return_presentation_data['Date'] = Carbon::parse($investment_return->date)->format('d/m/Y') ;

        $investment_return_presentation_data['Amount'] = $investment_return->amount;
        
        $investment_return_presentation_data['Account'] = Account::find($investment_return->account_id)->name;

        $investment_return_presentation_data['Note'] = $investment_return->note;

        $investment_return_presentation_data['Finalized By'] = User::find($investment_return->finalized_by)->username;

        $investment_return_presentation_data['Finalized At'] = Carbon::parse($investment_return->finalized_at)->format('d/m/Y h:i A');


        $investment_return_data['presentation_data'] = $investment_return_presentation_data;

        $investment_return_data['investment_return_id'] = $investment_return->id;
 
        $transaction_pool->data = json_encode($investment_return_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeInvestmentReturn($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storeInvestmentReturn($transaction_pool)
    {
        $investment_return_id = json_decode($transaction_pool->data)->investment_return_id;

        $investment_return = InvestmentReturn::find($investment_return_id);

        $investment_return->status = 'Approved';

        $investment_return->save();


        $investment = $investment_return->investment;

        $total_paid = $investment->returns()->where('status', '=', 'Approved')->sum('amount');

        if ($total_paid < $investment->amount)
        {
            $investment->return_status = 'Partial';
        } 
        else if($total_paid == $investment->amount)
        {
            $investment->return_status = 'Paid';
        }

        $investment->save();

        $investment_return->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR INVESTMENT RETURN

        if($investment->type == 'Receivable')
        {
            $amount = $investment_return->amount * (-1);
            $cash_flow_type = 'Debit';
        }
        else if($investment->type == 'Payable')
        {
            $amount = $investment_return->amount;
            $cash_flow_type = 'Credit';
        }

        $account_statement_arr = [
            'type' => 'Investment Return',
            'reference_id' => $investment_return->id,
            'amount' => $amount,
            'account_id' => $investment_return->account_id,
            'cash_flow_type' => $cash_flow_type,
        ];

        return $account_statement_arr;
    }

    public function tempStorePurchasePayment($payment, $supplier_account_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';

        $transaction_pool->poolable_id = $payment->id;
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Purchase No'] = Purchase::find($payment->paymentable_id)->purchase_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;
        
        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Supplier'] = Supplier::where('unique_id', '=', Account::find($supplier_account_id)->name )->first()->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Purchase Payment';

        $payment_data['supplier_account_id'] = $supplier_account_id;
 
        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storePurchasePayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storePurchasePayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $supplier_account_id = json_decode($transaction_pool->data)->supplier_account_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $purchase = $payment->paymentable;

        $total_paid = $purchase->payments()->where('status', '=', 'Approved')->sum('amount');

        $purchase_amount = $purchase->total_amount; 

        if ($total_paid == 0)
        {
            $purchase->payment_status = 'Due';
        } 
        else if($total_paid == $purchase_amount)
        {
            $purchase->payment_status = 'Paid';
        }
        else{
            $purchase->payment_status = 'Partial';
        }

        $purchase->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR PURCHASE PAYMENT

        $account_statement_arr = [
            'type' => 'Purchase Payment',
            'reference_id' => $payment->id,
            'amount' => $payment->amount * (-1),
            'account_id' => $payment->account_id,
            'supplier_account_id' => $supplier_account_id,
            'cash_flow_type' => 'Debit',
        ];

        return $account_statement_arr;
    }    
    
    public function tempStorePurchaseReturnPayment($payment, $supplier_account_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY

        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Purchase Return No'] = PurchaseReturn::find($payment->paymentable_id)->purchase_return_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;

        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Supplier'] = Supplier::where('unique_id', '=', Account::find($supplier_account_id)->name )->first()->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Purchase Return Payment';

        $payment_data['supplier_account_id'] = $supplier_account_id;

        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storePurchaseReturnPayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
    }

    public function storePurchaseReturnPayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $supplier_account_id = json_decode($transaction_pool->data)->supplier_account_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $purchase_return = $payment->paymentable;

        $total_paid = $purchase_return->payments()->where('status', '=', 'Approved')->sum('amount');

        if ($total_paid == 0)
        {
            $purchase_return->payment_status = 'Due';
        } 
        else if($total_paid == $purchase_return->total_amount)
        {
            $purchase_return->payment_status = 'Paid';
        }
        else{
            $purchase_return->payment_status = 'Partial';
        }

        $purchase_return->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR PURCHASE RETURN PAYMENT

        $account_statement_arr = [
            'type' => 'Purchase Return Payment',
            'reference_id' => $payment->id,
            'amount' => $payment->amount,
            'account_id' => $payment->account_id,
            'supplier_account_id' => $supplier_account_id,
            'cash_flow_type' => 'Credit',
        ];

        return $account_statement_arr;

    }

    public function tempStoreSalePayment($payment, $customer_account_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Invoice No'] = Sale::find($payment->paymentable_id)->invoice_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;
        
        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Customer'] = Customer::where('unique_id', '=', Account::find($customer_account_id)->name )->first()->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Sale Payment';

        $payment_data['customer_account_id'] = $customer_account_id;
 
        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeSalePayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storeSalePayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $customer_account_id = json_decode($transaction_pool->data)->customer_account_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $sale = $payment->paymentable;

        $total_paid = $sale->payments()->where('status', '=', 'Approved')->sum('amount');

        $sale_amount = $sale->total_amount;

        if ($total_paid == 0)
        {
            $sale->payment_status = 'Due';
        } 
        else if($total_paid == $sale_amount)
        {
            $sale->payment_status = 'Paid';
        }
        else{
            $sale->payment_status = 'Partial';
        }

        $sale->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR SALE PAYMENT

        $account_statement_arr = [
            'type' => 'Sale Payment',
            'reference_id' => $payment->id,
            'amount' => $payment->amount,
            'account_id' => $payment->account_id,
            'customer_account_id' => $customer_account_id,
            'cash_flow_type' => 'Credit',
        ];

        return $account_statement_arr;
    }    
    
    public function tempStoreProjectPayment($payment, $client_account_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Project Id'] = Project::find($payment->paymentable_id)->project_id;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;
        
        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Client'] = Client::where('unique_id', '=', Account::find($client_account_id)->name )->first()->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Project Payment';

        $payment_data['client_account_id'] = $client_account_id;
 
        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeProjectPayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storeProjectPayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $client_account_id = json_decode($transaction_pool->data)->client_account_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $project = $payment->paymentable;

        $total_paid = $project->payments()->where('status', '=', 'Approved')->sum('amount');

        $project_amount = $project->project_phases->sum('amount');

        if ($total_paid == 0)
        {
            $project->payment_status = 'Due';
        } 
        else if($total_paid == $project_amount)
        {
            $project->payment_status = 'Paid';
        }
        else{
            $project->payment_status = 'Partial';
        }

        $project->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR SALE PAYMENT

        $account_statement_arr = [
            'type' => 'Project Payment',
            'reference_id' => $payment->id,
            'amount' => $payment->amount,
            'account_id' => $payment->account_id,
            'customer_account_id' => $client_account_id,
            'cash_flow_type' => 'Credit',
        ];

        return $account_statement_arr;
    }

    public function tempStoreSaleReturnPayment($payment, $customer_account_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY

        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Sale Return No'] = SaleReturn::find($payment->paymentable_id)->sale_return_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;

        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Customer'] = Customer::where('unique_id', '=', Account::find($customer_account_id)->name )->first()->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Sale Return Payment';

        $payment_data['customer_account_id'] = $customer_account_id;

        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeSaleReturnPayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
    }

    public function storeSaleReturnPayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $customer_account_id = json_decode($transaction_pool->data)->customer_account_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $sale_return = $payment->paymentable;

        $total_paid = $sale_return->payments()->where('status', '=', 'Approved')->sum('amount');

        if ($total_paid == 0)
        {
            $sale_return->payment_status = 'Due';
        } 
        else if($total_paid == $sale_return->total_amount)
        {
            $sale_return->payment_status = 'Paid';
        }
        else{
            $sale_return->payment_status = 'Partial';
        }

        $sale_return->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR PURCHASE RETURN PAYMENT

        $account_statement_arr = [
            'type' => 'Sale Return Payment',
            'reference_id' => $payment->id,
            'amount' => $payment->amount * (-1),
            'account_id' => $payment->account_id,
            'supplier_account_id' => $customer_account_id,
            'cash_flow_type' => 'Debit',
        ];

        return $account_statement_arr;

    }

    public function tempStoreWholesalePayment($payment, $customer_account_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY
        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Invoice No'] = Wholesale::find($payment->paymentable_id)->invoice_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;
        
        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Customer'] = Customer::where('unique_id', '=', Account::find($customer_account_id)->name )->first()->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Wholesale Payment';

        $payment_data['customer_account_id'] = $customer_account_id;
 
        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeWholesalePayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
        
    }

    public function storeWholesalePayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $customer_account_id = json_decode($transaction_pool->data)->customer_account_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $wholesale = $payment->paymentable;

        $total_paid = $wholesale->payments()->where('status', '=', 'Approved')->sum('amount');

        $wholesale_amount = $wholesale->total_amount;

        if ($total_paid == 0)
        {
            $wholesale->payment_status = 'Due';
        } 
        else if($total_paid == $wholesale_amount)
        {
            $wholesale->payment_status = 'Paid';
        }
        else{
            $wholesale->payment_status = 'Partial';
        }

        $wholesale->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR WHOLESALE PAYMENT

        $account_statement_arr = [
            'type' => 'Wholesale Payment',
            'reference_id' => $payment->id,
            'amount' => $payment->amount,
            'account_id' => $payment->account_id,
            'customer_account_id' => $customer_account_id,
            'cash_flow_type' => 'Debit',
        ];

        return $account_statement_arr;
    }

    public function tempStoreWholesaleReturnPayment($payment, $customer_account_id)
    {
        $transaction_pool = new TransactionPool();

        $transaction_pool->poolable_type = 'App\Models\Payment';
        
        $transaction_pool->action_type = 'Create';


        // FOR PRESENTATION PURPOSE ONLY

        $payment_presentation_data['Payment No'] = $payment->payment_no;

        $payment_presentation_data['Wholesale Return No'] = WholesaleReturn::find($payment->paymentable_id)->wholesale_return_no;

        $payment_presentation_data['Date'] = Carbon::parse($payment->date)->format('d/m/Y') ;

        $payment_presentation_data['Amount'] = $payment->amount;

        $payment_presentation_data['Account'] = Account::find($payment->account_id)->name;

        $payment_presentation_data['Customer'] = Customer::where('unique_id', '=', Account::find($customer_account_id)->name )->first()->name;

        $payment_presentation_data['Note'] = $payment->note;

        $payment_presentation_data['Finalized By'] = User::find($payment->finalized_by)->username;

        $payment_presentation_data['Finalized At'] = Carbon::parse($payment->finalized_at)->format('d/m/Y h:i A');


        $payment_data['presentation_data'] = $payment_presentation_data;

        $payment_data['payment_id'] = $payment->id;

        $payment_data['payment_for'] = 'Wholesale Return Payment';

        $payment_data['customer_account_id'] = $customer_account_id;

        $transaction_pool->data = json_encode($payment_data);

        $transaction_pool->save();

        if(in_array('transaction_pool.ignore', session('user_permissions'))){
            
            $account_statement_arr = $this->storeWholesaleReturnPayment($transaction_pool);

            $this->account_statement_service->store($account_statement_arr);

            $transaction_pool->delete();

        }
    }

    public function storeWholesaleReturnPayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $customer_account_id = json_decode($transaction_pool->data)->customer_account_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Approved';

        $payment->save();


        $wholesale_return = $payment->paymentable;

        $total_paid = $wholesale_return->payments()->where('status', '=', 'Approved')->sum('amount');

        if ($total_paid == 0)
        {
            $wholesale_return->payment_status = 'Due';
        } 
        else if($total_paid == $wholesale_return->total_amount)
        {
            $wholesale_return->payment_status = 'Paid';
        }
        else{
            $wholesale_return->payment_status = 'Partial';
        }

        $wholesale_return->save();

        $payment->transactionPool()->save($transaction_pool);

        // ACCOUNT STATEMENT ENTRY FOR WHOLESALE RETURN PAYMENT

        $account_statement_arr = [
            'type' => 'Wholesale Return Payment',
            'reference_id' => $payment->id,
            'amount' => $payment->amount * (-1),
            'account_id' => $payment->account_id,
            'supplier_account_id' => $customer_account_id,
            'cash_flow_type' => 'Debit',
        ];

        return $account_statement_arr;

    }

    public function rejectPayment($transaction_pool)
    {
        $payment_id = json_decode($transaction_pool->data)->payment_id;

        $payment = Payment::find($payment_id);

        $payment->status = 'Rejected';

        $payment->save();

        $payment->transactionPool()->save($transaction_pool);

        $transaction_pool->action_type = 'Rejected';
            
        $transaction_pool->save();

        $payment->delete();

    }
}