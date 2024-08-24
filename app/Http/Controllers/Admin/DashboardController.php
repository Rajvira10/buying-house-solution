<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Revenue;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\Wholesale;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\WholesaleReturn;
use App\Http\Controllers\Controller;
use App\Services\AccountStatementService;

class DashboardController extends Controller
{
    private $start_date;
    private $end_date;

    public function setDates($request)
    {
        $this->start_date = $request->start_date ?? Carbon::now()->startOfMonth();
        $this->end_date = $request->end_date != 'undefined' ? Carbon::parse($request->end_date)->endOfDay() : $request->start_date ?? Carbon::now()->endOfMonth()->endOfDay();
    }

    public function index(Request $request)
    {
        $this->setDates($request);

        // $total_customers = $this->totalCustomers();

        // $total_suppliers = $this->totalSuppliers();

        $total_employees = $this->totalEmployees();

        // $total_pcs_in_stock = $this->totalPcsInStock();

        // $total_value_of_current_stock = $this->totalValueOfCurrentStock();

        // $total_gudam_pcs_in_stock = $this->totalPcsInGudamStock();

        // $total_receivable = $this->totalReceivable();

        // $total_payable = $this->totalPayable();

        $total_expense = $this->totalExpense();

        $total_revenue = $this->totalRevenue();

        // $total_purchase = $this->totalPurchase();

        // $total_sale = $this->totalSale();

        // $total_wholesale = $this->totalWholesale();

        // $total_debit = $this->totalDebit();

        // $total_credit = $this->totalCredit();

        // $total_gross_profit = $this->grossProfit();

        // $total_net_profit = $this->netProfit();

        // $blocks = [$total_suppliers, $total_customers, $total_employees, $total_gudam_pcs_in_stock, $total_pcs_in_stock, $total_value_of_current_stock, $total_receivable, $total_payable];
        
        // $date_blocks = [$total_expense, $total_revenue, $total_purchase, $total_sale,  $total_credit, $total_debit, $total_gross_profit, $total_net_profit];

        // if($request->ajax())
        // {
        //     return response()->json(
        //         [
        //             'blocks' => $blocks,
        //             'date_blocks' => $date_blocks
        //         ]
        //     );
        // }

        return view('admin.dashboard');
    }

    public function totalCustomers()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Customers',
                'amount' => 0,
                'class' => 'info',
                'unit' => ''
            ];
        }

        $total_customers = Customer::where('warehouse_id', '=', session('user_warehouse')->id)
        ->count();

        $customers = [
            'name' => 'Total Customers',
            'amount' => $total_customers,
            'class' => 'info',
            'unit' => ''
        ];

        return $customers;
    }    
    
    public function totalSuppliers()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Suppliers',
                'amount' => 0,
                'class' => 'danger',
                'unit' => ''
            ];
        }        

        $total_suppliers = Supplier::where('warehouse_id', '=', session('user_warehouse')->id)
        ->count();

        $customers = [
            'name' => 'Total Suppliers',
            'amount' => $total_suppliers,
            'class' => 'danger',
            'unit' => ''
        ];

        return $customers;
    }

    public function totalEmployees()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Employees',
                'amount' => 0,
                'class' => 'warning',
                'unit' => ''
            ];
        }

        $total_employees = Employee::where('warehouse_id', '=', session('user_warehouse')->id)
        ->count();

        $employees = [
            'name' => 'Total Employees',
            'amount' => $total_employees,
            'class' => 'warning',
            'unit' => ''
        ];

        return $employees;
    }

    public function totalPcsInGudamStock()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Current Gudam Stock',
                'amount' => 0,
                'class' => 'secondary',
                'unit' => 'Pcs. '
            ];
        }

        $total_pcs_in_gudam_stock = Inventory::where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('gudam_available_quantity');

        $total_pcs_in_gudam_stock = [
            'name' => 'Current Gudam Stock',
            'amount' => $total_pcs_in_gudam_stock,
            'class' => 'secondary',
            'unit' => 'Pcs. '
        ];

        return $total_pcs_in_gudam_stock;
    }

    public function totalPcsInStock()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Current Stock',
                'amount' => 0,
                'class' => 'primary',
                'unit' => 'Pcs. '
            ];
        }

        $total_pcs_in_stock = Inventory::where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('available_quantity');

        
        $total_pcs_in_stock = [
            'name' => 'Current Stock',
            'amount' => $total_pcs_in_stock,
            'class' => 'primary',
            'unit' => 'Pcs. '
        ];

        return $total_pcs_in_stock;
    }

    public function totalValueOfCurrentStock()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Value of Current Stock',
                'amount' => 0,
                'class' => 'info',
                'unit' => 'BDT '
            ];
        }

        $total_value_of_current_stock = Inventory::rightJoin('products', 'inventories.product_id', '=', 'products.id')
            ->join('warehouses', 'inventories.warehouse_id', '=', 'warehouses.id')
            ->join('prices', 'prices.product_id', '=', 'products.id')
            ->select(
                DB::raw('SUM(prices.purchase_price * COALESCE(inventories.available_quantity, 0)) as total_cost')
            )
            ->where('warehouses.id', '=', session('user_warehouse')->id)
            ->first();

        $total_value_of_current_stock = $total_value_of_current_stock->total_cost;

        $total_value_of_current_stock = [
            'name' => 'Value of Current Stock',
            'amount' => number_format($total_value_of_current_stock,2),
            'class' => 'info',
            'unit' => 'BDT '
        ];

        return $total_value_of_current_stock;
    }

    public function totalReceivable()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Receivable',
                'amount' => 0,
                'class' => 'success',
                'unit' => 'BDT '
            ];
        }

        $customers = Customer::with('account')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->get();

        $totalBalance = 0;

        $customers->each(function ($customer) use (&$totalBalance) {
            $account = $customer->account;
            $accountStatementService = new AccountStatementService();
            $current_balance = $accountStatementService->getCurrentBalance($account->id);

            if($current_balance > 0)
            {
                $totalBalance += $current_balance;
            }
        });

        $suppliers = Supplier::with('account')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->get();

        $suppliers->each(function ($supplier) use (&$totalBalance) {
            $account = $supplier->account;
            $accountStatementService = new AccountStatementService();
            $current_balance = $accountStatementService->getCurrentBalance($account->id);

            if($current_balance > 0)
            {
                $totalBalance += $current_balance;
            }
        });

        $receivable = [
            'name' => 'Total Receivable',
            'amount' => $totalBalance,
            'class' => 'success',
            'unit' => 'BDT '
        ];

        return $receivable;
    }

    public function totalPayable()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Payable',
                'amount' => 0,
                'class' => 'danger',
                'unit' => 'BDT '
            ];
        }

        $suppliers = Supplier::with('account')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->get();

        $totalBalance = 0;

        $suppliers->each(function ($supplier) use (&$totalBalance) {
            $account = $supplier->account;
            $accountStatementService = new AccountStatementService();
            $current_balance = $accountStatementService->getCurrentBalance($account->id);

            if($current_balance < 0)
            {
                $totalBalance += $current_balance;
            }
        });

        $customers = Customer::with('account')
            ->where('warehouse_id', '=', session('user_warehouse')->id)
            ->get();

        $customers->each(function ($customer) use (&$totalBalance) {
            $account = $customer->account;
            $accountStatementService = new AccountStatementService();
            $current_balance = $accountStatementService->getCurrentBalance($account->id);

            if($current_balance < 0)
            {
                $totalBalance += $current_balance;
            }
        });

        $payable = [
            'name' => 'Total Payable',
            'amount' => $totalBalance,
            'class' => 'danger',
            'unit' => 'BDT '
        ];

        return $payable;
    }

    public function totalExpense()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Expense',
                'amount' => 0,
                'class' => 'warning',
                'unit' => 'BDT '
            ];
        }

        $total_expense = Expense::whereBetween('expenses.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('amount');


        $total_expense = [
            'name' => 'Total Expense',
            'amount' => $total_expense,
            'class' => 'warning',
            'unit' => 'BDT '
        ];

        return $total_expense;
    }

    public function totalRevenue()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Revenue',
                'amount' => 0,
                'class' => 'success',
                'unit' => 'BDT '
            ];
        }

        $total_revenue = Revenue::whereBetween('revenues.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('amount');


        $total_revenue = [
            'name' => 'Total Revenue',
            'amount' => $total_revenue,
            'class' => 'success',
            'unit' => 'BDT '
        ];

        return $total_revenue;
    }

    public function totalPurchase()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Purchase',
                'amount' => 0,
                'class' => 'danger',
                'unit' => 'BDT '
            ];
        }

        $total_purchase = Purchase::whereBetween('purchases.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_purchase = [
            'name' => 'Total Purchase',
            'amount' => $total_purchase,
            'class' => 'danger',
            'unit' => 'BDT '
        ];

        return $total_purchase;
    }

    public function totalSale()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Sale',
                'amount' => 0,
                'class' => 'success',
                'unit' => 'BDT '
            ];
        }
        
        $total_sale = Sale::whereBetween('sales.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_sale = [
            'name' => 'Total Sale',
            'amount' => $total_sale,
            'class' => 'success',
            'unit' => 'BDT '
        ];

        return $total_sale;
    }

    public function totalWholesale()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Wholesale',
                'amount' => 0,
                'class' => 'primary',
                'unit' => 'BDT '
            ];
        }

        $total_wholesale = Wholesale::whereBetween('wholesales.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_wholesale = [
            'name' => 'Total Wholesale',
            'amount' => $total_wholesale,
            'class' => 'primary',
            'unit' => 'BDT '
        ];

        return $total_wholesale;
    }

    public function totalDebit()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Debit',
                'amount' => 0,
                'class' => 'danger',
                'unit' => 'BDT '
            ];
        }

        $total_sale_return = SaleReturn::whereBetween('sale_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_wholesale_return = WholesaleReturn::whereBetween('wholesale_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_debit = $this->totalPurchase()['amount'] + $this->totalExpense()['amount'] + $total_sale_return + $total_wholesale_return;

        $total_debit = [
            'name' => 'Total Debit',
            'amount' => $total_debit,
            'class' => 'danger',
            'unit' => 'BDT '
        ];

        return $total_debit;
    }

    public function totalCredit()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Total Credit',
                'amount' => 0,
                'class' => 'success',
                'unit' => 'BDT '
            ];
        }

        $total_purchase_return = PurchaseReturn::whereBetween('purchase_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_credit = $this->totalSale()['amount'] + $this->totalWholesale()['amount'] + $total_purchase_return + $this->totalRevenue()['amount'];

        $total_credit = [
            'name' => 'Total Credit',
            'amount' => $total_credit,
            'class' => 'success',
            'unit' => 'BDT '
        ];

        return $total_credit;
    }

    public function grossProfit()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Gross Profit',
                'amount' => 0,
                'class' => 'warning',
                'unit' => 'BDT '
            ];
        }

        $total_sale = $this->totalSale()['amount'];

        $total_wholesale = $this->totalWholesale()['amount'];

        $total_purchase = $this->totalPurchase()['amount'];

        $total_purchase_return = PurchaseReturn::whereBetween('purchase_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_sale_return = SaleReturn::whereBetween('sale_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_wholesale_return = WholesaleReturn::whereBetween('wholesale_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_gross_profit = ($total_sale + $total_wholesale + $total_purchase_return) - ($total_purchase + $total_sale_return + $total_wholesale_return);

        $total_gross_profit = [
            'name' => 'Gross Profit',
            'amount' => $total_gross_profit,
            'class' => 'warning',
            'unit' => 'BDT '
        ];

        return $total_gross_profit;
    }

    public function netProfit()
    {
        if(session('user_warehouse') == null)
        {
            return [
                'name' => 'Net Profit',
                'amount' => 0,
                'class' => 'success',
                'unit' => 'BDT '
            ];
        }
        
        $total_sale = $this->totalSale()['amount'];

        $total_wholesale = $this->totalWholesale()['amount'];

        $total_purchase = $this->totalPurchase()['amount'];

        $total_expense = $this->totalExpense()['amount'];

        $total_revenue = $this->totalRevenue()['amount'];

        $total_purchase_return = PurchaseReturn::whereBetween('purchase_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_sale_return = SaleReturn::whereBetween('sale_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $total_wholesale_return = WholesaleReturn::whereBetween('wholesale_returns.date', [$this->start_date, $this->end_date])
        ->where('warehouse_id', '=', session('user_warehouse')->id)
        ->sum('total_amount');

        $net_profit = ($total_sale + $total_wholesale + $total_purchase_return + $total_revenue) - ($total_purchase + $total_expense + $total_sale_return + $total_wholesale_return);

        $net_profit = [
            'name' => 'Net Profit',
            'amount' => $net_profit,
            'class' => 'success',
            'unit' => 'BDT '
        ];

        return $net_profit;
    }

}
