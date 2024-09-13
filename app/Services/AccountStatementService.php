<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\Wholesale;
use App\Models\Investment;
use App\Models\SaleReturn;
use App\Models\LoanPayback;
use App\Models\CashInCashOut;
use App\Models\MoneyTransfer;
use App\Models\PurchaseReturn;
use App\Models\WholesaleReturn;
use App\Models\AccountStatement;
use App\Models\InvestmentReturn;


class AccountStatementService
{
    public function get($account_id)
    {

        $date = Carbon::today();

        if(!$this->checkOpeningBalance($date, $account_id))
        {
            $this->setOpeningAndClosingBalance($date->copy()->subMonth(), $account_id);
        }

        $account_statements = AccountStatement::join('accounts', 'accounts.id', '=', 'account_statements.account_id')
            ->select([
                'account_statements.*',
                'accounts.name as account_name',
                ])
            ->where('account_statements.account_id', '=', $account_id)
            ->orderBy('account_statements.statement_date', 'asc')
            ->get();

        $balance = 0;

        foreach ($account_statements as $row) 
        {
            if ($row->type == 'Opening Balance' || $row->type == 'Closing Balance') 
            {
                $balance = $row->amount;

                $row->transaction_date = Carbon::parse($row->statement_date)->format('d/m/Y');

                $row->reference = "";

                $row->statement_date = Carbon::parse($row->statement_date)->format('d/m/Y');

                $row->debit = "";

                $row->credit = "";

                $row->balance = number_format($balance, 2, '.', ',');
            }
            else 
            {
                $balance = $balance + $row->amount;

                if($row->type == 'Expense')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $expense = $payment->paymentable;

                    $reference_data = [
                        'reference' => $expense->expense_id,
                        'expense_category' => $expense->expenseCategory->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Revenue')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $revenue = $payment->paymentable;

                    $reference_data = [
                        'reference' => $revenue->revenue_id,
                        'revenue_category' => $revenue->revenueCategory->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Loan')
                {
                    $loan = Loan::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($loan->date)->format('d/m/Y');

                    $reference_data = [
                        'reference' => $loan->loan_id,
                        'loan_client' => $loan->loanClient->name,
                        'note' => $loan->note,
                        'finalized_by' => $loan->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Loan Payback')
                {
                    $loan_payback = LoanPayback::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($loan_payback->date)->format('d/m/Y');

                    $reference_data = [
                        'reference' => $loan_payback->loan_id,
                        'note' => $loan_payback->note,
                        'finalized_by' => $loan_payback->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Investment')
                {
                    $investment = Investment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($investment->date)->format('d/m/Y');

                    $reference_data = [
                        'reference' => $investment->investment_id,
                        'investor' => $investment->investor->name,
                        'note' => $investment->note,
                        'finalized_by' => $investment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Investment Return')
                {
                    $investment_return = InvestmentReturn::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($investment_return->date)->format('d/m/Y');

                    $reference_data = [
                        'reference' => $investment_return->investment_id,
                        'note' => $investment_return->note,
                        'finalized_by' => $investment_return->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }
                
                else if($row->type == 'Purchase')
                {
                    $purchase = Purchase::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($purchase->date)->format('d/m/Y');

                    $reference_data = [
                        'supplier' => $purchase->supplier->name,
                        'note' => $purchase->note,
                        'finalized_by' => $purchase->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Purchase Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $purchase = $payment->paymentable;

                    $reference_data = [
                        'supplier' => $purchase->supplier->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }
                else if($row->type == 'Purchase Return')
                {
                    $purchase_return = PurchaseReturn::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($purchase_return->date)->format('d/m/Y');

                    $reference_data = [
                        'supplier' => $purchase_return->purchase->supplier->name,
                        'note' => $purchase_return->note,
                        'finalized_by' => $purchase_return->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }
                else if($row->type == 'Purchase Return Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $purchase_return = $payment->paymentable;

                    $reference_data = [
                        'supplier' => $purchase_return->purchase->supplier->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }
                else if($row->type == 'Sale')
                {
                    $sale = Sale::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($sale->date)->format('d/m/Y');

                    $reference_data = [
                        'customer' => $sale->customer->name,
                        'note' => $sale->note,
                        'finalized_by' => $sale->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Project')
                {
                    $project = Project::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($project->start_date)->format('d/m/Y');

                    $reference_data = [
                        'reference' => $project->project_id,
                        'client' => $project->client->name,
                        'note' => $project->note,
                        'finalized_by' => $project->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Sale Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $sale = $payment->paymentable;

                    $reference_data = [
                        'customer' => $sale->customer->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Project Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $project = $payment->paymentable;

                    $reference_data = [
                        'reference_id' => $project->project_id,
                        'client' => $project->client->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Sale Return')
                {
                    $sale_return = SaleReturn::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($sale_return->date)->format('d/m/Y');

                    $reference_data = [
                        'customer' => $sale_return->sale->customer->name,
                        'note' => $sale_return->note,
                        'finalized_by' => $sale_return->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Sale Return Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $sale_return = $payment->paymentable;

                    $reference_data = [
                        'customer' => $sale_return->sale->customer->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Wholesale')
                {
                    $wholesale = Wholesale::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($wholesale->date)->format('d/m/Y');

                    $reference_data = [
                        'customer' => $wholesale->customer->name,
                        'note' => $wholesale->note,
                        'finalized_by' => $wholesale->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Wholesale Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $wholesale = $payment->paymentable;

                    $reference_data = [
                        'customer' => $wholesale->customer->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Wholesale Return')
                {
                    $wholesale_return = WholesaleReturn::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($wholesale_return->date)->format('d/m/Y');

                    $reference_data = [
                        'customer' => $wholesale_return->wholesale->customer->name,
                        'note' => $wholesale_return->note,
                        'finalized_by' => $wholesale_return->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Wholesale Return Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $wholesale_return = $payment->paymentable;

                    $reference_data = [
                        'customer' => $wholesale_return->wholesale->customer->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Money Transfer')
                {
                    $money_transfer = MoneyTransfer::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($money_transfer->date)->format('d/m/Y');

                    $reference_data = [
                        'sender_receiver' => $money_transfer->senderAccount->name . ' ->' . $money_transfer->receiverAccount->name,
                        'note' => $money_transfer->note,
                        'finalized_by' => $money_transfer->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Add Money' || $row->type == 'Withdraw Money')
                {
                    $cash_in_cash_out = CashInCashOut::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($cash_in_cash_out->date)->format('d/m/Y');

                    $reference_data = [
                        'note' => $cash_in_cash_out->note,
                        'finalized_by' => $cash_in_cash_out->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $reference_data = [
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                $row->statement_date = Carbon::parse($row->statement_date)->format('d/m/Y');

                $row->debit = $row->cash_flow_type == 'Debit' ? number_format(($row->amount * (-1)), 2, '.', ',') : '';

                $row->credit = $row->cash_flow_type == 'Credit' ? number_format(($row->amount), 2, '.', ',') : '';

                $row->balance = number_format($balance, 2, '.', ',');
            }
        
        }

        return $account_statements;
    }

    public function balanceSheet()
    {
        //all the accounts statements together with the account name but only one opening balance and one closing balance for each month
        $account_statements = AccountStatement::join('accounts', 'accounts.id', '=', 'account_statements.account_id')
            ->select([
                'account_statements.*',
                'accounts.name as account_name',
                ])
            ->orderBy('account_statements.statement_date', 'asc')
            ->get();

        $balance = 0;

        foreach ($account_statements as $row) 
        {
            if ($row->type == 'Opening Balance' || $row->type == 'Closing Balance') 
            {
                $balance = $row->amount;

                $row->transaction_date = Carbon::parse($row->statement_date)->format('d/m/Y');

                $row->reference = "";

                $row->statement_date = Carbon::parse($row->statement_date)->format('d/m/Y');

                $row->debit = "";

                $row->credit = "";

                $row->balance = number_format($balance, 2, '.', ',');
            }
            else 
            {
                $balance = $balance + $row->amount;

                if($row->type == 'Expense')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $expense = $payment->paymentable;

                    $reference_data = [
                        'expense_category' => $expense->expenseCategory->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Revenue')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $revenue = $payment->paymentable;

                    $reference_data = [
                        'revenue_category' => $revenue->revenueCategory->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Loan')
                {
                    $loan = Loan::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($loan->date)->format('d/m/Y');

                    $reference_data = [
                        'loan_client' => $loan->loanClient->name,
                        'note' => $loan->note,
                        'finalized_by' => $loan->finalizedBy->username,
                    ];
                }

                else if($row->type == 'Loan Payback')
                {
                    $loan_payback = LoanPayback::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($loan_payback->date)->format('d/m/Y');

                    $reference_data = [
                        'note' => $loan_payback->note,
                        'finalized_by' => $loan_payback->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Investment')
                {
                    $investment = Investment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($investment->date)->format('d/m/Y');

                    $reference_data = [
                        'investor' => $investment->investor->name,
                        'note' => $investment->note,
                        'finalized_by' => $investment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Investment Return')
                {
                    $investment_return = InvestmentReturn::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($investment_return->date)->format('d/m/Y');

                    $reference_data = [
                        'note' => $investment_return->note,
                        'finalized_by' => $investment_return->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }
                
                else if($row->type == 'Purchase')
                {
                    $purchase = Purchase::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($purchase->date)->format('d/m/Y');

                    $reference_data = [
                        'supplier' => $purchase->supplier->name,
                        'note' => $purchase->note,
                        'finalized_by' => $purchase->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                }

                else if($row->type == 'Purchase Payment')
                {
                    $payment = Payment::find($row->reference_id);

                    $row->transaction_date = Carbon::parse($payment->date)->format('d/m/Y');

                    $purchase = $payment->paymentable;

                    $reference_data = [
                        'supplier' => $purchase->supplier->name,
                        'note' => $payment->note,
                        'finalized_by' => $payment->finalizedBy->username,
                    ];

                    $row->reference = implode(" | ", $reference_data);
                
                }
            }
        }

    }

    public function setInitialOpeningBalance($account_id, $opening_balance)
    {
        
        $account_statement = new AccountStatement();

        $account_statement->type = 'Opening Balance';

        $account_statement->account_id = $account_id;

        $account_statement->amount = $opening_balance ?? 0;

        $account_statement->statement_date = date('Y-m-01');

        $account_statement->save();

    }

    public function checkOpeningBalance($date, $account_id)
    {
        $start = $date->year . "-" . $date->month . "-01";

        $opening_balance = AccountStatement::where('type', '=', 'Opening Balance')
            ->whereDate('statement_date', '=', $start)
            ->where('account_id', '=', $account_id)
            ->get();
        
        if(count($opening_balance) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function setOpeningAndClosingBalance($date, $account_id)
    {
        $start = $date->year . "-" . $date->month . "-01";
        
        $end = $date->year . "-" . $date->month . "-" . $date->daysInMonth . " 23:59:58";

        $overall_balance = 0;

        if(!$this->checkOpeningBalance($date, $account_id))
        {
            $overall_balance = $this->setOpeningAndClosingBalance($date->copy()->subMonth(), $account_id);
        }
        else
        {
            $overall_balance = AccountStatement::whereDate('statement_date', '>=', $start)
                ->whereDate('statement_date', '<=', $end)
                ->where('account_id', '=', $account_id)
                ->sum('amount');
        }

        // CLOSING BALANCE FOR CURRENT MONTH
        $closing_balance = new AccountStatement();
        
        $closing_balance->type = "Closing Balance";

        $closing_balance->amount = $overall_balance;

        $closing_balance->account_id = $account_id;

        $closing_balance->statement_date = $end;
        
        $closing_balance->save();


        // OPENING BALANCE FOR NEXT MONTH
        $opening_balance = new AccountStatement();
        
        $opening_balance->type = "Opening Balance";

        $opening_balance->amount = $overall_balance;

        $opening_balance->account_id = $account_id;

        $date = $date->addMonth();
        
        $opening_balance->statement_date = $date->year . "-" . $date->month . "-01";
        
        $opening_balance->save();

        
        return $overall_balance;
    }

    public function store($account_statement_data_arr)
    {
        $date = Carbon::today();

        if(!$this->checkOpeningBalance($date, $account_statement_data_arr['account_id']))
        {
            $this->setOpeningAndClosingBalance($date->copy()->subMonth(), $account_statement_data_arr['account_id']);
        }

        $account_statement = new AccountStatement();

        $account_statement->type = $account_statement_data_arr['type'];

        $account_statement->reference_id = $account_statement_data_arr['reference_id'];

        $account_statement->amount = $account_statement_data_arr['amount'];
        
        $account_statement->cash_flow_type = $account_statement_data_arr['cash_flow_type'];

        $account_statement->account_id = $account_statement_data_arr['account_id'];

        $account_statement->statement_date = Carbon::now();

        $account_statement->save();

        if(array_key_exists('customer_account_id', $account_statement_data_arr))
        {
            $customer_account_statement = new AccountStatement();

            $customer_account_statement->type = $account_statement_data_arr['type'];

            $customer_account_statement->reference_id = $account_statement_data_arr['reference_id'];

            $customer_account_statement->amount = - $account_statement_data_arr['amount'];
            
            $customer_account_statement->cash_flow_type = $account_statement_data_arr['cash_flow_type'] == 'Debit' ? 'Credit' : 'Debit';

            $customer_account_statement->account_id = $account_statement_data_arr['customer_account_id'];

            $customer_account_statement->statement_date = Carbon::now();

            $customer_account_statement->save();
        }

        if(array_key_exists('supplier_account_id', $account_statement_data_arr))
        {
            
            $supplier_account_statement = new AccountStatement();

            $supplier_account_statement->type = $account_statement_data_arr['type'];

            $supplier_account_statement->reference_id = $account_statement_data_arr['reference_id'];

            $supplier_account_statement->amount = - $account_statement_data_arr['amount'];
            
            $supplier_account_statement->cash_flow_type =  $account_statement_data_arr['cash_flow_type'] == 'Debit' ? 'Credit' : 'Debit';

            $supplier_account_statement->account_id = $account_statement_data_arr['supplier_account_id'];

            $supplier_account_statement->statement_date = Carbon::now();

            $supplier_account_statement->save();
            
        }
    }

    public function getCurrentBalance($account_id)
    {
        $date = Carbon::today();

        if(!$this->checkOpeningBalance($date, $account_id))
        {
            $this->setOpeningAndClosingBalance($date->copy()->subMonth(), $account_id);
        }

        $start = $date->year . "-" . $date->month . "-01";

        $end = $date->year . "-" . $date->month . "-" . $date->daysInMonth . " 23:59:58";

        $overall_balance = AccountStatement::whereDate('statement_date', '>=', $start)
            ->whereDate('statement_date', '<=', $end)
            ->where('account_id', '=', $account_id)
            ->sum('amount');

        return $overall_balance;
    }

}