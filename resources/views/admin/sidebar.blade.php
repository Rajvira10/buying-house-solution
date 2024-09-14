@inject('settings', 'App\Services\SettingsService')
@inject('permissions', 'App\Services\PermissionService')

@php
    $settings = $settings->getSettings();
@endphp


<!-- ========== SIDEBAR MODULE ========== -->
<div class="app-menu navbar-menu">


    <div class="navbar-brand-box mt-3">
        @php
            $segment1 = request()->segment(1);
            $segment2 = request()->segment(2);

        @endphp
        <a href="{{ route('admin-dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('public/admin-assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <h2 style="color: #fff; padding-top: 20px; margin-bottom: 0"></h2>
            </span>
        </a>

        <a href="{{ route('admin-dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ $settings->favicon->absolute_path ?? asset('public/admin-assets/images/logo-sm.png') }}"
                    alt="" height="22">
            </span>
            <span class="logo-lg">
                {{-- <h2 style="color: #fff; padding-top: 20px; margin-bottom: 0">
                    {{ $settings->short_name ?? 'Company Name' }}</h2> --}}
                <img src="{{ $settings->logo->absolute_path ?? asset('public/admin-assets/images/logo-sm.png') }}"
                    alt="" height="40">
            </span>
        </a>

        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>

    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">Menu</span></li>



                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin-dashboard') }}" role="button"
                        aria-expanded="false">
                        <i class="bx bx-home-alt"></i> <span data-key="t-dashboards">Home</span>
                    </a>
                </li>

                @if ($permissions->nav_user_group)

                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (in_array(session('view_name'), ['admin.users.role.index', 'admin.users.user.index'])) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarUsers" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarUsers">
                            <i class="ri-user-line"></i> <span data-key="t-users">User</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarUsers">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_users_option)
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.users.user.index' ? 'active' : '' }}"
                                            data-key="t-user">Users</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_roles_option)
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.users.role.index' ? 'active' : '' }}"
                                            data-key="t-role">Roles</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>

                @endif

                @if ($permissions->nav_hrm_group)

                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.hrm.department.index',
                                    'admin.hrm.employee.index',
                                    'admin.hrm.salary_sheet.index',
                                    'admin.hrm.payroll.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarHRM" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarHRM">
                            <i class="ri-briefcase-fill"></i> <span data-key="t-users">HRM</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarHRM">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_department_option)
                                    <li class="nav-item">
                                        <a href="{{ route('departments.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.hrm.department.index' ? 'active' : '' }}"
                                            data-key="t-department">Department</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_employee_option)
                                    <li class="nav-item">
                                        <a href="{{ route('employees.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.hrm.employee.index' ? 'active' : '' }}"
                                            data-key="t-employee">Employee</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_salary_sheet_option)
                                    <li class="nav-item">
                                        <a href="{{ route('payrolls.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.hrm.salary_sheet.index' ? 'active' : '' }}"
                                            data-key="t-employee">Salary Sheet</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_payroll_option)
                                    <li class="nav-item">
                                        <a href="{{ route('payrolls.payroll.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.hrm.payroll.index' ? 'active' : '' }}"
                                            data-key="t-employee">Payroll</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($permissions->nav_crm_group)

                    <li class="nav-item">

                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.crm.buyer.index',
                                    'admin.crm.factory.index',
                                    'admin.crm.supplier.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp

                        <a class="nav-link menu-link {{ $active }}" href="#sidebarCRM" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarCRM">
                            <i class="ri-customer-service-2-line"></i> <span data-key="t-crm">CRM</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarCRM">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_buyer_option)
                                    <li class="nav-item">
                                        <a href="{{ route('buyers.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.crm.buyer.index' ? 'active' : '' }}"
                                            data-key="t-buyer">Buyer</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_factory_option)
                                    <li class="nav-item">
                                        <a href="{{ route('factories.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.crm.factory.index' ? 'active' : '' }}"
                                            data-key="t-factory">Factory</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_supplier_option)
                                    <li class="nav-item">
                                        <a href="{{ route('suppliers.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.crm.supplier.index' ? 'active' : '' }}"
                                            data-key="t-supplier">Supplier</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- @if ($permissions->nav_call_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.call.calls.index',
                                    'admin.call.call_type.index',
                                    'admin.call.call_status.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarCall"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarCall">
                            <i class="ri-phone-fill"></i> <span data-key="t-calls">Call Management</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarCall">
                            <ul class="nav nav-sm flex-column">


                                @if ($permissions->nav_call_types_option)
                                    <li class="nav-item">
                                        <a href="{{ route('call_types.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.call.call_type.index' ? 'active' : '' }}"
                                            data-key="t-call-type">Call Type</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_call_status_option)
                                    <li class="nav-item">
                                        <a href="{{ route('call_statuses.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.call.call_status.index' ? 'active' : '' }}"
                                            data-key="t-call-status">Call Status</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_calls_option)
                                    <li class="nav-item">
                                        <a href="{{ route('calls.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.call.calls.index' ? 'active' : '' }}"
                                            data-key="t-call">Calls</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif --}}

                {{-- @if ($permissions->nav_meeting_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.meeting.meetings.index',
                                    'admin.meeting.meeting_title.index',
                                    'admin.meeting.meeting_type.index',
                                    'admin.meeting.meeting_status.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarMeeting"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarMeeting">
                            <i class="ri-calendar-check-fill"></i> <span data-key="t-meetings">Meeting
                                Management</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarMeeting">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_meeting_types_option)
                                    <li class="nav-item">
                                        <a href="{{ route('meeting_types.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.meeting.meeting_type.index' ? 'active' : '' }}"
                                            data-key="t-meeting-type">Meeting Type</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_meeting_title_option)
                                    <li class="nav-item">
                                        <a href="{{ route('meeting_titles.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.meeting.meeting_title.index' ? 'active' : '' }}"
                                            data-key="t-meeting-title">Meeting Title</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_meeting_status_option)
                                    <li class="nav-item">
                                        <a href="{{ route('meeting_statuses.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.meeting.meeting_status.index' ? 'active' : '' }}"
                                            data-key="t-meeting-status">Meeting Status</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_meetings_option)
                                    <li class="nav-item">
                                        <a href="{{ route('meetings.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.meeting.meetings.index' ? 'active' : '' }}"
                                            data-key="t-meeting">Meetings</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif --}}

                {{-- @if ($permissions->nav_project_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.project.projects.index',
                                    'admin.project.project_status.index',
                                    'admin.project.project_type.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarProject"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarProject">
                            <i class="ri-projector-2-line"></i> <span data-key="t-projects">Project</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarProject">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_project_status_option)
                                    <li class="nav-item">
                                        <a href="{{ route('project_statuses.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.project.project_status.index' ? 'active' : '' }}"
                                            data-key="t-project-status">Project Status</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_project_types_option)
                                    <li class="nav-item">
                                        <a href="{{ route('project_types.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.project.project_type.index' ? 'active' : '' }}"
                                            data-key="t-project-type">Project Type</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_projects_option)
                                    <li class="nav-item">
                                        <a href="{{ route('projects.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.project.projects.index' ? 'active' : '' }}"
                                            data-key="t-project">Projects</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif --}}

                {{-- @if ($permissions->nav_expense_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.expenses.expense_categories.index',
                                    'admin.expenses.expense_categories.create',
                                    'admin.expenses.expense_categories.edit',
                                    'admin.expenses.expense.index',
                                    'admin.expenses.expense.create',
                                    'admin.expenses.expense.edit',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarExpense"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarExpense">
                            <i class="ri-coins-line"></i> <span data-key="t-expenses">Expense</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarExpense">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_expense_categories_option)
                                    <li class="nav-item">
                                        <a href="{{ route('expense_categories.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.expenses.expense_categories.index' ? 'active' : '' }}"
                                            data-key="t-expense-categories">Expense Categories</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_expenses_option)
                                    <li class="nav-item">
                                        <a href="{{ route('expenses.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.expenses.expense.index' ? 'active' : '' }}"
                                            data-key="t-expense">Expenses</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif --}}

                {{-- @if ($permissions->nav_revenue_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.revenues.revenue_categories.index',
                                    'admin.revenues.revenue.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarRevenue"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarRevenue">
                            <i class="las la-money-bill-wave"></i> <span data-key="t-revenues">Revenue</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarRevenue">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_revenue_categories_option)
                                    <li class="nav-item">
                                        <a href={{ route('revenue_categories.index') }} target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.revenues.revenue_categories.index' ? 'active' : '' }}"
                                            data-key="t-revenue-categories">Revenue Categories</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_revenues_option)
                                    <li class="nav-item">
                                        <a href="{{ route('revenues.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.revenues.revenue.index' ? 'active' : '' }}"
                                            data-key="t-revenue">Revenues</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif --}}

                {{-- @if ($permissions->nav_payment_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (in_array(session('view_name'), ['admin.payments.payment.index'])) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarPayment"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarPayment">
                            <i class="ri-paypal-fill"></i> <span data-key="t-payments">Payments</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarPayment">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('payments.index') }}" target="_self"
                                        class="nav-link {{ session('view_name') == 'admin.payments.payment.index' ? 'active' : '' }}"
                                        data-key="t-account-categories">Payment</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif --}}

                @if ($permissions->nav_query_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.query.trim.index',
                                    'admin.query.query.index',
                                    'admin.query.product.index',
                                    'admin.query.product_type.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarQuery"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarQuery">
                            <i class="ri-questionnaire-line"></i> <span data-key="t-queries">Queries</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarQuery">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_product_type_option)
                                    <li class="nav-item">
                                        <a href="{{ route('product_types.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.query.product_type.index' ? 'active' : '' }}"
                                            data-key="t-product-type">Product Type</a>
                                    </li>
                                @endif
                                @if ($permissions->nav_product_option)
                                    <li class="nav-item">
                                        <a href="{{ route('products.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.query.product.index' ? 'active' : '' }}"
                                            data-key="t-product">Product</a>
                                    </li>
                                @endif
                                {{-- @if ($permissions->nav_trim_option)
                                    <li class="nav-item">
                                        <a href="{{ route('trims.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.query.trim.index' ? 'active' : '' }}"
                                            data-key="t-trim">Trim</a>
                                    </li>
                                @endif --}}
                                @if ($permissions->nav_query_option)
                                    <li class="nav-item">
                                        <a href="{{ route('queries.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.query.query.index' ? 'active' : '' }}"
                                            data-key="t-query">Queries</a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif

                <li class="nav-item">
                    @php
                        $active = $show = '';
                        if (in_array(session('view_name'), ['admin.orders.order.index'])) {
                            $active = 'active';
                            $show = 'show';
                        }
                    @endphp
                    <a class="nav-link menu-link {{ $active }}" href="#sidebarOrder" role="button"
                        aria-expanded="false">
                        <i class="ri-shopping-cart-2-line"></i> <span data-key="t-orders">Orders</span>
                    </a>

                </li>


                {{-- @if ($permissions->nav_account_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.accounts.account_categories.index',
                                    'admin.accounts.account.index',
                                    'admin.accounts.account_statement.index',
                                    'admin.accounts.cash_in_cash_out.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarAccount"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarAccount">
                            <i class="ri-bank-line"></i> <span data-key="t-accounts">Account</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarAccount">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_account_categories_option)
                                    <li class="nav-item">
                                        <a href="{{ route('account_categories.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.accounts.account_categories.index' ? 'active' : '' }}"
                                            data-key="t-account-categories">Account Categories</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_accounts_option)
                                    <li class="nav-item">
                                        <a href="{{ route('accounts.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.accounts.account.index' ? 'active' : '' }}"
                                            data-key="t-account">Accounts</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_account_statements_option)
                                    <li class="nav-item">
                                        <a href="{{ route('account_statements.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.accounts.account_statement.index' ? 'active' : '' }}"
                                            data-key="t-account-statements">Account Statements</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_add_withdraw_money_option)
                                    <li class="nav-item">
                                        <a href="{{ route('cash_in_cash_outs.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.accounts.cash_in_cash_out.index' ? 'active' : '' }}"
                                            data-key="t-account-statements">Add/Withdraw Money</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($permissions->nav_financial_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.financials.money_transfer.index',
                                    'admin.financials.loan.index',
                                    'admin.financials.investment.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarFinancial"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarFinancial">
                            <i class="ri-money-dollar-circle-line"></i> <span data-key="t-financials">Financial</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarFinancial">
                            <ul class="nav nav-sm flex-column">
                                @if ($permissions->nav_money_transfer_option)
                                    <li class="nav-item">
                                        <a href="{{ route('money_transfers.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.financials.money_transfer.index' ? 'active' : '' }}"
                                            data-key="t-money-transfers">Money Transfer</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_loan_option)
                                    <li class="nav-item">
                                        <a href="{{ route('loans.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.financials.loan.index' ? 'active' : '' }}"
                                            data-key="t-loans">Loan</a>
                                    </li>
                                @endif

                                @if ($permissions->nav_investment_option)
                                    <li class="nav-item">
                                        <a href="{{ route('investments.index') }}" target="_self"
                                            class="nav-link {{ session('view_name') == 'admin.financials.investment.index' ? 'active' : '' }}"
                                            data-key="t-investments">Investment</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($permissions->nav_transaction_pool_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (in_array(session('view_name'), ['admin.transaction-pool.index'])) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarTransaction"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarTransaction">
                            <i class="ri-database-line"></i> <span data-key="t-transactions">Transaction
                                Pool</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarTransaction">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href={{ route('transaction_pools.index') }} target="_self"
                                        class="nav-link {{ session('view_name') == 'admin.transaction-pool.index' ? 'active' : '' }}"
                                        data-key="t-transaction">Transaction List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif --}}

                {{-- @if ($permissions->nav_report_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (in_array(session('view_name'), ['admin.reports.report.index'])) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarReport"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarReport">
                            <i class="ri-file-list-3-line"></i> <span data-key="t-transactions">Report</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarReport">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href={{ route('reports.index') }} target="_self"
                                        class="nav-link {{ session('view_name') == 'admin.reports.report.index' ? 'active' : '' }}"
                                        data-key="t-transaction">Reports</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif --}}

                {{-- @if ($permissions->nav_config_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (
                                in_array(session('view_name'), [
                                    'admin.config.warehouses.index',
                                    'admin.products.unit.index',
                                    'admin.config.areas.index',
                                    'admin.config.brands.index',
                                    'admin.config.references.index',
                                    'admin.config.taxes.index',
                                ])
                            ) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link {{ $active }}" href="#sidebarConfig"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarConfig">
                            <i class=" ri-tools-fill"></i> <span data-key="t-transactions">Config</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $show }}" id="sidebarConfig">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href={{ route('warehouses.index') }} target="_self"
                                        class="nav-link {{ session('view_name') == 'admin.config.warehouses.index' ? 'active' : '' }}"
                                        data-key="t-warehouse">Warehouse</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('units.index') }}"
                                        class="nav-link {{ session('view_name') == 'admin.products.unit.index' ? 'active' : '' }}"
                                        data-key="t-units">Units
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href={{ route('areas.index') }} target="_self"
                                        class="nav-link
                                    {{ session('view_name') == 'admin.config.areas.index' ? 'active' : '' }}">Area</a>
                                </li>
                                <li class="nav-item">
                                    <a href={{ route('brands.index') }} target="_self"
                                        class="nav-link
                                    {{ session('view_name') == 'admin.config.brands.index' ? 'active' : '' }}">Brand</a>
                                </li>
                                <li class="nav-item">
                                    <a href={{ route('references.index') }} target="_self"
                                        class="nav-link
                                    {{ session('view_name') == 'admin.config.references.index' ? 'active' : '' }}">Reference</a>
                                </li>
                                <li class="nav-item">
                                    <a href={{ route('taxes.index') }} target="_self"
                                        class="nav-link
                                    {{ session('view_name') == 'admin.config.taxes.index' ? 'active' : '' }}">Tax</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endif --}}

                @if ($permissions->nav_setting_group)
                    <li class="nav-item">
                        @php
                            $active = $show = '';
                            if (in_array(session('view_name'), ['admin.settings.index'])) {
                                $active = 'active';
                                $show = 'show';
                            }
                        @endphp
                        <a class="nav-link menu-link  {{ $active }}" href="{{ route('settings.index') }}"
                            role="button" aria-expanded="false">
                            <i class="ri-settings-5-line"></i> <span data-key="t-dashboards">Settings</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- ========== --end-- SIDEBAR MODULE ========== -->
