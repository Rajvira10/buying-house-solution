@extends('admin.layout')
@section('title', 'Reports')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary ">
                                <h4 class="card-title text-light">Reports</h4>
                            </div>
                            <div class="card-body">
                                {{-- <div>
                                    <h5 class="card-title pb-2">Customer</h5>
                                    <a href="{{ route('customers.index') }}">
                                        <button class="btn btn-primary">
                                            Customer List
                                        </button>
                                    </a>
                                    <a href="{{ route('customers.due_report') }}">
                                        <button class="btn btn-primary">
                                            Due Report
                                        </button>
                                    </a>
                                    <hr>
                                </div>
                                <div>
                                    <h5 class="card-title pb-2">Supplier</h5>
                                    <a href="{{ route('suppliers.index') }}">
                                        <button class="btn btn-primary">
                                            Supplier List
                                        </button>
                                    </a>
                                    <a href="{{ route('suppliers.due_report') }}">
                                        <button class="btn btn-primary">
                                            Due Report
                                        </button>
                                    </a>
                                    <hr>
                                </div>
                                <div>
                                    <h5 class="card-title pb-2">Product & Inventory</h5>
                                    <a href="{{ route('inventories.index') }}">
                                        <button class="btn btn-primary">Stock Report</button>
                                    </a>
                                    <a href="{{ route('prices.index') }}">
                                        <button class="btn btn-primary">Product Price Report</button>
                                    </a>
                                    <a href="{{ route('product_stock_history.index') }}">
                                        <button class="btn btn-primary">Stock History Report</button>
                                    </a>
                                    <a href="{{ route('stock_adjustments.index') }}">
                                        <button class="btn btn-primary">Stock Adjustment Report</button>
                                    </a>
                                    <a href="{{ route('inventories.alert_stock') }}">
                                        <button class="btn btn-primary">Alert Stock Report</button>
                                    </a>
                                    <hr>
                                </div>
                                <div>
                                    <h5 class="card-title pb-2">Purchase</h5>
                                    <a href="{{ route('purchases.index') }}">
                                        <button class="btn btn-primary">
                                            Purchase Report
                                        </button>
                                    </a>
                                    <a href="{{ route('purchase_returns.index') }}">
                                        <button class="btn btn-primary">
                                            Purchase Return Report
                                        </button>
                                    </a>
                                    <hr>
                                </div> --}}
                                {{-- <div>
                                    <h5 class="card-title pb-2">Sale</h5>
                                    <a href="{{ route('sales.index') }}">
                                        <button class="btn btn-primary">Sale Report</button>
                                    </a>
                                    <a href="{{ route('sales.top_customers') }}">
                                        <button class="btn btn-primary">Top Sale Customers Report</button>
                                    </a>
                                    <a href="{{ route('sale_returns.index') }}">
                                        <button class="btn btn-primary">
                                            Sale Return Report
                                        </button>
                                    </a>
                                    <a href="{{ route('manualsales.index') }}">
                                        <button class="btn btn-primary">
                                            ManualSale Report
                                        </button>
                                    </a>
                                    <hr>
                                </div> --}}
                                <div>
                                    <h5 class="card-title pb-2">Expense</h5>
                                    <a href="{{ route('expense_categories.index') }}">
                                        <button class="btn btn-primary">Expense Category Report</button>
                                    </a>
                                    <a href="{{ route('expenses.index') }}">
                                        <button class="btn btn-primary">Expense Report</button>
                                    </a>
                                    <hr>
                                </div>
                                <div>
                                    <h5 class="card-title pb-2">Revenue</h5>
                                    <a href="{{ route('revenue_categories.index') }}">
                                        <button class="btn btn-primary">Revenue Category Report</button>
                                    </a>
                                    <a href="{{ route('revenues.index') }}">
                                        <button class="btn btn-primary">Revenue Report</button>
                                    </a>
                                    <hr>
                                </div>
                                <div>
                                    <h5 class="card-title pb-2">Account</h5>
                                    <a href="{{ route('account_statements.index') }}">
                                        <button class="btn btn-primary">Account Statement Report</button>
                                    </a>
                                    <hr>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
