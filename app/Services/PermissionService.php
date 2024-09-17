<?php

namespace App\Services;


class PermissionService
{
    
    public $nav_user_group =  false;

    public $nav_users_option = false;

    public $nav_roles_option = false;

    
    public $nav_hrm_group = false;

    public $nav_department_option = false;

    public $nav_employee_option = false;

    public $nav_salary_sheet_option = false;

    public $nav_payroll_option = false;


    public $nav_crm_group = false;

    public $nav_factory_option = false;

    public $nav_buyer_option = false;

    public $nav_supplier_option = false;

    public $nav_call_group = false;

    public $nav_call_types_option = false;

    public $nav_call_status_option = false;

    public $nav_calls_option = false;


    public $nav_meeting_group = false;

    public $nav_meeting_types_option = false;

    public $nav_meeting_status_option = false;

    public $nav_meeting_title_option = false;

    public $nav_meetings_option = false;


    public $nav_project_group = false;

    public $nav_project_types_option = false;

    public $nav_project_status_option = false;

    public $nav_projects_option = false;


    public $nav_query_group = false;

    public $nav_trim_option = false;

    public $nav_query_option = false;


    public $nav_config_group = false;

    public $nav_product_type_option = false;

    public $nav_product_option = false;


    public $nav_order_group = false;

    public $nav_orders_option = false;


    public $nav_expense_group = false;

    public $nav_expense_categories_option = false;

    public $nav_expenses_option = false;


    public $nav_revenue_group = false;

    public $nav_revenue_categories_option = false;

    public $nav_revenues_option = false;


    public $nav_payment_group = false;

    
    public $nav_account_group = false;

    public $nav_account_categories_option = false;
    
    public $nav_accounts_option = false;

    public $nav_account_statements_option = false;

    public $nav_add_withdraw_money_option = false;
    


    public $nav_financial_group = false;

    public $nav_money_transfer_option = false;

    public $nav_loan_option = false;

    public $nav_investment_option = false;


    public $nav_transaction_pool_group = false;

    public $nav_transaction_list_option = false;


    public $nav_report_group = false;


    public $nav_setting_group = false;

    public function __construct()
    {
        $this->prepareNavigation();
    }

    public function prepareNavigation()
    {
        $permissions = session('user_permissions');

        if($permissions == "all")
        {
            $this->setAllTrue();

            return;
        }

        // User Group

        if(in_array('user.index', $permissions) || in_array('role.index', $permissions))
        {
            $this->nav_user_group = true;
        }

        if(in_array('user.index', $permissions))
        {
            $this->nav_users_option = true;
        }

        if(in_array('role.index', $permissions))
        {
            $this->nav_roles_option = true;
        }

        // HRM Group

        if(in_array('department.index', $permissions) || in_array('employee.index', $permissions) || in_array('salary_sheet.index', $permissions) || in_array('payroll.index', $permissions))
        {
            $this->nav_hrm_group = true;
        }

        if(in_array('department.index', $permissions))
        {
            $this->nav_department_option = true;
        }

        if(in_array('employee.index', $permissions))
        {
            $this->nav_employee_option = true;
        }

        if(in_array('salary_sheet.index', $permissions))
        {
            $this->nav_salary_sheet_option = true;
        }

        if(in_array('payroll.index', $permissions))
        {
            $this->nav_payroll_option = true;
        }

        // CRM Group

        if(in_array('factory.index', $permissions) || in_array('buyer.index', $permissions) || in_array('supplier.index', $permissions))
        {
            $this->nav_crm_group = true;
        }

        if(in_array('factory.index', $permissions))
        {
            $this->nav_factory_option = true;
        }

        if(in_array('buyer.index', $permissions))
        {
            $this->nav_buyer_option = true;
        }

        if(in_array('supplier.index', $permissions))
        {
            $this->nav_supplier_option = true;
        }

        // Call Group

        if(in_array('call.index', $permissions) || in_array('call_type.index', $permissions) || in_array('call_status.index', $permissions))
        {
            $this->nav_call_group = true;
        }

        if(in_array('call_type.index', $permissions))
        {
            $this->nav_call_types_option = true;
        }

        if(in_array('call_status.index', $permissions))
        {
            $this->nav_call_status_option = true;
        }

        if(in_array('call.index', $permissions))
        {
            $this->nav_calls_option = true;
        }

        // Meeting Group


        if(in_array('meeting.index', $permissions) || in_array('meeting_type.index', $permissions) || in_array('meeting_status.index', $permissions) || in_array('meeting_title.index', $permissions))
        {
            $this->nav_meeting_group = true;
        }

        if(in_array('meeting_type.index', $permissions))
        {
            $this->nav_meeting_types_option = true;
        }

        if(in_array('meeting_status.index', $permissions))
        {
            $this->nav_meeting_status_option = true;
        }

        if(in_array('meeting_title.index', $permissions))
        {
            $this->nav_meeting_title_option = true;
        }   

        if(in_array('meeting.index', $permissions))
        {
            $this->nav_meetings_option = true;
        }

        // Project Group

        if(in_array('project.index', $permissions) || in_array('project_type.index', $permissions) || in_array('project_status.index', $permissions))
        {
            $this->nav_project_group = true;
        }

        if(in_array('project_type.index', $permissions))
        {
            $this->nav_project_types_option = true;
        }

        if(in_array('project_status.index', $permissions))
        {
            $this->nav_project_status_option = true;
        }

        if(in_array('project.index', $permissions))
        {
            $this->nav_projects_option = true;
        }

        // Query Group

        if(in_array('query.index', $permissions) || in_array('trim.index', $permissions))
        {
            $this->nav_query_group = true;
        }


        if(in_array('trim.index', $permissions))
        {
            $this->nav_trim_option = true;
        }

        if(in_array('query.index', $permissions))
        {
            $this->nav_query_option = true;
        }

        // Config Group
    
        if(in_array('product.index', $permissions) || in_array('product_type.index', $permissions))
        {
            $this->nav_config_group = true;
        }

        if(in_array('product_type.index', $permissions))
        {
            $this->nav_product_type_option = true;
        }

        if(in_array('product.index', $permissions))
        {
            $this->nav_product_option = true;
        }

        // Order Group

        if(in_array('order.index', $permissions))
        {
            $this->nav_order_group = true;
        }

        if(in_array('order.index', $permissions))
        {
            $this->nav_orders_option = true;
        }

        // Expense Group

        if(in_array('expense.index', $permissions) || in_array('expense_category.index', $permissions))
        {
            $this->nav_expense_group = true;
        }

        if(in_array('expense.index', $permissions))
        {
            $this->nav_expenses_option = true;
        }

        if(in_array('expense_categories.index', $permissions))
        {
            $this->nav_expense_categories_option = true;
        }

        // Revenue Group

        if(in_array('revenue.index', $permissions) || in_array('revenue_category.index', $permissions))
        {
            $this->nav_revenue_group = true;
        }

        if(in_array('revenue.index', $permissions))
        {
            $this->nav_revenues_option = true;
        }

        if(in_array('revenue_categories.index', $permissions))
        {
            $this->nav_revenue_categories_option = true;
        }


        // Payment Group

        if(in_array('payment.index', $permissions))
        {
            $this->nav_payment_group = true;
        }


        // Account Group

        if(in_array('account.index', $permissions) || in_array('account_category.index', $permissions) || in_array('account_statement.index', $permissions) || in_array('add_withdraw_money.index', $permissions))
        {
            $this->nav_account_group = true;
        }

        if(in_array('account.index', $permissions))
        {
            $this->nav_accounts_option = true;
        }

        if(in_array('account_category.index', $permissions))
        {
            $this->nav_account_categories_option = true;
        }

        if(in_array('account_statement.index', $permissions))
        {
            $this->nav_account_statements_option = true;
        }

        if(in_array('add_withdraw_money.index', $permissions))
        {
            $this->nav_add_withdraw_money_option = true;
        }

        // Financial Group

        if(in_array('money_transfer.index', $permissions) || in_array('loan.index', $permissions) || in_array('investment.index', $permissions))
        {
            $this->nav_financial_group = true;
        }

        if(in_array('money_transfer.index', $permissions))
        {
            $this->nav_money_transfer_option = true;
        }

        if(in_array('loan.index', $permissions))
        {
            $this->nav_loan_option = true;
        }

        if(in_array('investment.index', $permissions))
        {
            $this->nav_investment_option = true;
        }

        // Transaction Pool Group

        if(in_array('transaction_pool.index', $permissions))
        {
            $this->nav_transaction_pool_group = true;

            $this->nav_transaction_list_option = true;
        }

        // Report Group

        if(in_array('report.index', $permissions))
        {
            $this->nav_report_group = true;
        }

        // Setting Group

        if(in_array('setting.index', $permissions))
        {
            $this->nav_setting_group = true;
        }
    }

    public function setAllTrue()
    {
        $this->nav_user_group =  true;

        $this->nav_users_option =  true;

        $this->nav_roles_option =  true;

        $this->nav_hrm_group =  true;

        $this->nav_department_option =  true;

        $this->nav_employee_option =  true;

        $this->nav_salary_sheet_option =  true;

        $this->nav_payroll_option =  true;

        $this->nav_crm_group =  true;

        $this->nav_factory_option =  true;

        $this->nav_buyer_option =  true;

        $this->nav_supplier_option =  true;

        $this->nav_call_group =  true;

        $this->nav_call_types_option =  true;

        $this->nav_call_status_option =  true;

        $this->nav_calls_option =  true;

        $this->nav_meeting_group =  true;

        $this->nav_meeting_types_option =  true;

        $this->nav_meeting_status_option =  true;

        $this->nav_meeting_title_option =  true;

        $this->nav_meetings_option =  true;

        $this->nav_project_group =  true;

        $this->nav_project_types_option =  true;

        $this->nav_project_status_option =  true;

        $this->nav_projects_option =  true;

        $this->nav_query_group =  true;

        $this->nav_trim_option =  true;

        $this->nav_query_option =  true;

        $this->nav_config_group =  true;

        $this->nav_product_type_option =  true;

        $this->nav_product_option =  true;

        $this->nav_order_group =  true;

        $this->nav_orders_option =  true;

        $this->nav_expense_group =  true;

        $this->nav_expense_categories_option =  true;

        $this->nav_expenses_option =  true;

        $this->nav_revenue_group =  true;

        $this->nav_revenue_categories_option =  true;

        $this->nav_revenues_option =  true;

        $this->nav_payment_group =  true;

        $this->nav_account_group =  true;

        $this->nav_account_categories_option =  true;

        $this->nav_accounts_option =  true;

        $this->nav_account_statements_option =  true;

        $this->nav_add_withdraw_money_option =  true;

        $this->nav_financial_group =  true;

        $this->nav_money_transfer_option =  true;

        $this->nav_loan_option =  true;

        $this->nav_investment_option =  true;

        $this->nav_transaction_pool_group =  true;

        $this->nav_transaction_list_option =  true;

        $this->nav_report_group =  true;

        $this->nav_config_group =  true;

        $this->nav_product_type_option =  true;

        $this->nav_product_option =  true;

        $this->nav_setting_group =  true;
    
    }
}