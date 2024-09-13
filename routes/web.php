<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::view('/', 'welcome')->name('welcome');


Route::group(['namespace' => 'App\Http\Controllers\Admin'], function() {
    
    Route::view('/', 'admin.login')->name('admin-login')->middleware('guest');

    Route::post('authenticate', 'AuthenticationController@authenticate')->name('admin-authenticate');

    Route::group(['middleware' => ['auth:admin']], function() {

        Route::post('logout', 'AuthenticationController@logout')->name('admin-logout');

        Route::get('dashboard', 'DashboardController@index')->name('admin-dashboard');


        Route::group(['prefix' => 'user'], function(){

            Route::get('index', 'UserController@index')->name('users.index');
        
            Route::get('create', 'UserController@create')->name('users.create');

            Route::post('store', 'UserController@store')->name('users.store');

            Route::get('{user_id}', 'UserController@show')->name('users.show');

            Route::get('edit/{user_id}', 'UserController@edit')->name('users.edit');

            Route::post('update/{user_id}', 'UserController@update')->name('users.update');

            Route::get('user-roles/{user_id}', 'UserController@userRoles')->name('users.user_roles');

            Route::post('user-roles/{user_id}', 'UserController@assignRoles')->name('users.assign_roles');
              
        });
        

        Route::group(['prefix' => 'role'], function(){
                
            Route::get('index', 'RoleController@index')->name('roles.index');

            Route::get('create', 'RoleController@create')->name('roles.create');

            Route::get('edit/{role_id}', 'RoleController@edit')->name('roles.edit');

            Route::post('store', 'RoleController@store')->name('roles.store');

            Route::post('update/{role_id}', 'RoleController@update')->name('roles.update');
        
            Route::get('role-permissions/{role_id}', 'RoleController@rolePermissions')->name('roles.role_permissions');
    
            Route::post('role-permissions/{role_id}', 'RoleController@assignPermissions')->name('roles.assign_permissions');

        });


        Route::group(['prefix' => 'department'], function(){

            Route::get('index', 'DepartmentController@index')->name('departments.index');

            Route::get('create', 'DepartmentController@create')->name('departments.create');

            Route::post('store', 'DepartmentController@store')->name('departments.store');

            Route::get('edit/{department_id}', 'DepartmentController@edit')->name('departments.edit');

            Route::post('update/{department_id}', 'DepartmentController@update')->name('departments.update');
            
        });


        Route::group(['prefix' => 'employee'], function(){

            Route::get('index', 'EmployeeController@index')->name('employees.index');

            Route::get('create', 'EmployeeController@create')->name('employees.create');

            Route::post('store', 'EmployeeController@store')->name('employees.store');

            Route::get('{employee_id}', 'EmployeeController@show')->name('employees.show');

            Route::get('edit/{employee_id}', 'EmployeeController@edit')->name('employees.edit');

            Route::post('update/{employee_id}', 'EmployeeController@update')->name('employees.update');
            
            Route::post('delete', 'EmployeeController@destroy')->name('employees.destroy');

            Route::get('print/{employee_id}', 'EmployeeController@print')->name('employees.print');

            Route::get('salary-structure/create/{employee_id}', 'EmployeeController@createSalaryStructure')->name('employees.create_salary_structure');

            Route::post('salary-structure/store/{employee_id}', 'EmployeeController@storeSalaryStructure')->name('employees.store_salary_structure');

            Route::post('job-duration/activate/{employee_id}','EmployeeController@activate')->name('employees.activate');

            Route::post('job-duration/deactivate/{employee_id}','EmployeeController@deactivate')->name('employees.deactivate');

            Route::get('get-employees-by-warehouse', 'EmployeeController@getEmployeesByWarehouse')->name('employees.get_employees_by_warehouse');

        });


        Route::group(['prefix' => 'salary-sheet'], function(){
            
            Route::get('index', 'PayrollController@index')->name('payrolls.index');

            Route::get('create', 'PayrollController@create')->name('payrolls.create');
            
            Route::post('store', 'PayrollController@store')->name('payrolls.store');

            Route::get('{payroll_id}', 'PayrollController@show')->name('payrolls.show');

            Route::get('payroll_details/{payroll_id}', 'PayrollController@payrollDetails')->name('payrolls.payroll_details');

            Route::post('disburse/{payroll_detail_id}', 'PayrollController@disburse')->name('payrolls.disburse');

        });


        Route::group(['prefix' => 'payroll'], function(){

            Route::get('index', 'PayrollController@payrollIndex')->name('payrolls.payroll.index');

            Route::get('print/{payroll_id}', 'PayrollController@payrollPrint')->name('payrolls.payroll.print');

        });


        Route::group(['prefix' => 'client-source'], function(){

            Route::get('index', 'ClientSourceController@index')->name('client_sources.index');

            Route::get('create', 'ClientSourceController@create')->name('client_sources.create');

            Route::post('store', 'ClientSourceController@store')->name('client_sources.store');

            Route::get('edit/{client_source_id}', 'ClientSourceController@edit')->name('client_sources.edit');

            Route::post('update/{client_source_id}', 'ClientSourceController@update')->name('client_sources.update');

            Route::post('delete', 'ClientSourceController@destroy')->name('client_sources.destroy');
        });

        Route::group(['prefix' => 'client-status'], function(){

            Route::get('index', 'ClientStatusController@index')->name('client_statuses.index');

            Route::get('create', 'ClientStatusController@create')->name('client_statuses.create');

            Route::post('store', 'ClientStatusController@store')->name('client_statuses.store');

            Route::get('edit/{client_status_id}', 'ClientStatusController@edit')->name('client_statuses.edit');

            Route::post('update/{client_status_id}', 'ClientStatusController@update')->name('client_statuses.update');

            Route::post('delete', 'ClientStatusController@destroy')->name('client_statuses.destroy');
        });

        Route::group(['prefix' => 'business-category'], function(){

            Route::get('index', 'BusinessCategoryController@index')->name('business_categories.index');

            Route::get('create', 'BusinessCategoryController@create')->name('business_categories.create');

            Route::post('store', 'BusinessCategoryController@store')->name('business_categories.store');

            Route::get('edit/{business_category_id}', 'BusinessCategoryController@edit')->name('business_categories.edit');

            Route::post('update/{business_category_id}', 'BusinessCategoryController@update')->name('business_categories.update');

            Route::post('delete', 'BusinessCategoryController@destroy')->name('business_categories.destroy');
        });

        Route::group(['prefix' => 'interested-in'], function(){

            Route::get('index', 'InterestedInController@index')->name('interested_ins.index');

            Route::get('create', 'InterestedInController@create')->name('interested_ins.create');

            Route::post('store', 'InterestedInController@store')->name('interested_ins.store');

            Route::get('edit/{interested_in_id}', 'InterestedInController@edit')->name('interested_ins.edit');

            Route::post('update/{interested_in_id}', 'InterestedInController@update')->name('interested_ins.update');

            Route::post('delete', 'InterestedInController@destroy')->name('interested_ins.destroy');
        });

        Route::group(['prefix' => 'client'], function(){

            Route::get('index', 'ClientController@index')->name('clients.index');

            Route::get('create', 'ClientController@create')->name('clients.create');

            Route::post('store', 'ClientController@store')->name('clients.store');

            Route::get('{client_id}', 'ClientController@show')->name('clients.show');

            Route::get('edit/{client_id}', 'ClientController@edit')->name('clients.edit');

            Route::post('update/{client_id}', 'ClientController@update')->name('clients.update');

            Route::post('delete', 'ClientController@destroy')->name('clients.destroy');

            Route::post('add_contact_person', 'ClientController@addContactPerson')->name('clients.add_contact_person');

            Route::post('get_states', 'ClientController@getStates')->name('clients.get_states');

            Route::post('get_cities', 'ClientController@getCities')->name('clients.get_cities');

            Route::get('get-contact-persons/{client_id}', 'ClientController@getContactPersons')->name('clients.get_contact_persons');
        });

        Route::prefix('call-type')->group(function () {
            Route::get('index', 'CallTypeController@index')->name('call_types.index');
            Route::get('create', 'CallTypeController@create')->name('call_types.create');
            Route::post('store', 'CallTypeController@store')->name('call_types.store');
            Route::get('edit/{call_type_id}', 'CallTypeController@edit')->name('call_types.edit');
            Route::post('update/{call_type_id}', 'CallTypeController@update')->name('call_types.update');
            Route::post('delete', 'CallTypeController@destroy')->name('call_types.destroy');
        });

        Route::prefix('call-status')->group(function () {
            Route::get('index', 'CallStatusController@index')->name('call_statuses.index');
            Route::get('create', 'CallStatusController@create')->name('call_statuses.create');
            Route::post('store', 'CallStatusController@store')->name('call_statuses.store');
            Route::get('edit/{call_status_id}', 'CallStatusController@edit')->name('call_statuses.edit');
            Route::post('update/{call_status_id}', 'CallStatusController@update')->name('call_statuses.update');
            Route::post('delete', 'CallStatusController@destroy')->name('call_statuses.destroy');
        });

        Route::prefix('call')->group(function () {
            Route::get('index', 'CallController@index')->name('calls.index');
            Route::get('create', 'CallController@create')->name('calls.create');
            Route::post('store', 'CallController@store')->name('calls.store');
            Route::get('show/{call_id}', 'CallController@show')->name('calls.show');
            Route::get('edit/{call_id}', 'CallController@edit')->name('calls.edit');
            Route::post('update/{call_id}', 'CallController@update')->name('calls.update');
            Route::post('delete', 'CallController@destroy')->name('calls.destroy');
        });

        Route::prefix('meeting-type')->group(function () {
            Route::get('index', 'MeetingTypeController@index')->name('meeting_types.index');
            Route::get('create', 'MeetingTypeController@create')->name('meeting_types.create');
            Route::post('store', 'MeetingTypeController@store')->name('meeting_types.store');
            Route::get('edit/{meeting_type_id}', 'MeetingTypeController@edit')->name('meeting_types.edit');
            Route::post('update/{meeting_type_id}', 'MeetingTypeController@update')->name('meeting_types.update');
            Route::post('delete', 'MeetingTypeController@destroy')->name('meeting_types.destroy');
        });
        
        Route::prefix('meeting-status')->group(function () {
            Route::get('index', 'MeetingStatusController@index')->name('meeting_statuses.index');
            Route::get('create', 'MeetingStatusController@create')->name('meeting_statuses.create');
            Route::post('store', 'MeetingStatusController@store')->name('meeting_statuses.store');
            Route::get('edit/{meeting_status_id}', 'MeetingStatusController@edit')->name('meeting_statuses.edit');
            Route::post('update/{meeting_status_id}', 'MeetingStatusController@update')->name('meeting_statuses.update');
            Route::post('delete', 'MeetingStatusController@destroy')->name('meeting_statuses.destroy');
        });

        Route::prefix('meeting-title')->group(function () {
            Route::get('index', 'MeetingTitleController@index')->name('meeting_titles.index');
            Route::get('create', 'MeetingTitleController@create')->name('meeting_titles.create');
            Route::post('store', 'MeetingTitleController@store')->name('meeting_titles.store');
            Route::get('edit/{meeting_title_id}', 'MeetingTitleController@edit')->name('meeting_titles.edit');
            Route::post('update/{meeting_title_id}', 'MeetingTitleController@update')->name('meeting_titles.update');
            Route::post('delete', 'MeetingTitleController@destroy')->name('meeting_titles.destroy');
        });

        Route::prefix('meeting')->group(function () {
            Route::get('index', 'MeetingController@index')->name('meetings.index');
            Route::get('create', 'MeetingController@create')->name('meetings.create');
            Route::post('store', 'MeetingController@store')->name('meetings.store');
            Route::get('show/{meeting_id}', 'MeetingController@show')->name('meetings.show');
            Route::get('edit/{meeting_id}', 'MeetingController@edit')->name('meetings.edit');
            Route::post('update/{meeting_id}', 'MeetingController@update')->name('meetings.update');
            Route::post('delete', 'MeetingController@destroy')->name('meetings.destroy');
            Route::get('meeting-minutes/{meeting_id}', 'MeetingController@getMeetingMinutes')->name('meetings.meeting_minutes');
            Route::post('meeting-minutes', 'MeetingController@saveMeetingMinutes')->name('meetings.add_meeting_minutes');
        });

        Route::prefix('project-type')->group(function () {
            Route::get('index', 'ProjectTypeController@index')->name('project_types.index');
            Route::get('create', 'ProjectTypeController@create')->name('project_types.create');
            Route::post('store', 'ProjectTypeController@store')->name('project_types.store');
            Route::get('edit/{project_type_id}', 'ProjectTypeController@edit')->name('project_types.edit');
            Route::post('update/{project_type_id}', 'ProjectTypeController@update')->name('project_types.update');
            Route::post('delete', 'ProjectTypeController@destroy')->name('project_types.destroy');
        });

        Route::prefix('project-status')->group(function () {
            Route::get('index', 'ProjectStatusController@index')->name('project_statuses.index');
            Route::get('create', 'ProjectStatusController@create')->name('project_statuses.create');
            Route::post('store', 'ProjectStatusController@store')->name('project_statuses.store');
            Route::get('edit/{project_status_id}', 'ProjectStatusController@edit')->name('project_statuses.edit');
            Route::post('update/{project_status_id}', 'ProjectStatusController@update')->name('project_statuses.update');
            Route::post('delete', 'ProjectStatusController@destroy')->name('project_statuses.destroy');
        });

        Route::prefix('project')->group(function () {
            Route::get('index', 'ProjectController@index')->name('projects.index');
            Route::get('create', 'ProjectController@create')->name('projects.create');
            Route::post('store', 'ProjectController@store')->name('projects.store');
            Route::get('show/{project_id}', 'ProjectController@show')->name('projects.show');
            Route::get('edit/{project_id}', 'ProjectController@edit')->name('projects.edit');
            Route::post('update/{project_id}', 'ProjectController@update')->name('projects.update');
            Route::post('delete', 'ProjectController@destroy')->name('projects.destroy');
            Route::get('pending-payments/{project_id}', 'ProjectController@pendingPayments')->name('projects.pending_payments');
            Route::get('view-payments/{project_id}', 'ProjectController@approvedPayments')->name('projects.approved_payments');
            Route::get('payments/{project_id}', 'ProjectController@payment')->name('projects.payment');
            Route::post('store-payment/{project_id}', 'ProjectController@storePayment')->name('projects.storePayment');
            Route::get('edit-payment/{project_id}', 'ProjectController@editPayment')->name('projects.edit_payment');
            Route::post('update-payment/{project_id}', 'ProjectController@updatePayment')->name('projects.update_payment');
            Route::post('delete-payment', 'ProjectController@destroyPayment')->name('projects.destroy_payment');
        });
        

        Route::group(['prefix' => 'payment'], function(){

            Route::get('index', 'PaymentController@index')->name('payments.index');

            Route::get('create', 'PaymentController@create')->name('payments.create');

            Route::post('store', 'PaymentController@store')->name('payments.store');

            Route::get('edit/{payment_id}', 'PaymentController@edit')->name('payments.edit');

            Route::post('update/{payment_id}', 'PaymentController@update')->name('payments.update');

            Route::get('pending', 'PaymentController@pending')->name('payments.pending');

            Route::post('delete', 'PaymentController@destroy')->name('payments.destroy');

        });

        Route::group(['prefix' => 'buyer'], function(){

            Route::get('index', 'BuyerController@index')->name('buyers.index');

            Route::get('create', 'BuyerController@create')->name('buyers.create');

            Route::post('store', 'BuyerController@store')->name('buyers.store');

            Route::get('{buyer_id}', 'BuyerController@show')->name('buyers.show');

            Route::get('edit/{buyer_id}', 'BuyerController@edit')->name('buyers.edit');

            Route::post('update/{buyer_id}', 'BuyerController@update')->name('buyers.update');

            Route::post('delete', 'BuyerController@destroy')->name('buyers.destroy');

            Route::post('store-contact-person', 'BuyerController@storeContactPerson')->name('buyers.store_contact_person');

            Route::post('update-contact-person', 'BuyerController@updateContactPerson')->name('buyers.update_contact_person');

            Route::post('delete-contact-person', 'BuyerController@deleteContactPerson')->name('buyers.delete_contact_person');

        });

        Route::group(['prefix' => 'factory'], function(){

            Route::get('index', 'FactoryController@index')->name('factories.index');

            Route::get('create', 'FactoryController@create')->name('factories.create');

            Route::post('store', 'FactoryController@store')->name('factories.store');

            Route::get('{factory_id}', 'FactoryController@show')->name('factories.show');

            Route::get('edit/{factory_id}', 'FactoryController@edit')->name('factories.edit');

            Route::post('update/{factory_id}', 'FactoryController@update')->name('factories.update');

            Route::post('delete', 'FactoryController@destroy')->name('factories.destroy');

            Route::post('store-contact-person', 'FactoryController@storeContactPerson')->name('factories.store_contact_person');

            Route::post('update-contact-person', 'FactoryController@updateContactPerson')->name('factories.update_contact_person');

            Route::post('delete-contact-person', 'FactoryController@deleteContactPerson')->name('factories.delete_contact_person');
        });

        Route::group(['prefix' => 'supplier'], function(){

            Route::get('index', 'SupplierController@index')->name('suppliers.index');

            Route::get('create', 'SupplierController@create')->name('suppliers.create');

            Route::post('store', 'SupplierController@store')->name('suppliers.store');

            Route::get('{supplier_id}', 'SupplierController@show')->name('suppliers.show');

            Route::get('edit/{supplier_id}', 'SupplierController@edit')->name('suppliers.edit');

            Route::post('update/{supplier_id}', 'SupplierController@update')->name('suppliers.update');

            Route::post('delete', 'SupplierController@destroy')->name('suppliers.destroy');

            Route::post('store-contact-person', 'SupplierController@storeContactPerson')->name('suppliers.store_contact_person');

            Route::post('update-contact-person', 'SupplierController@updateContactPerson')->name('suppliers.update_contact_person');

            Route::post('delete-contact-person', 'SupplierController@deleteContactPerson')->name('suppliers.delete_contact_person');
        });

        Route::group(['prefix' => 'product-type'], function(){

            Route::get('index', 'ProductTypeController@index')->name('product_types.index');

            Route::get('create', 'ProductTypeController@create')->name('product_types.create');

            Route::post('store', 'ProductTypeController@store')->name('product_types.store');

            Route::get('edit/{product_type_id}', 'ProductTypeController@edit')->name('product_types.edit');

            Route::post('update/{product_type_id}', 'ProductTypeController@update')->name('product_types.update');

            Route::post('delete', 'ProductTypeController@destroy')->name('product_types.destroy');

        });

        Route::group(['prefix' => 'product'], function(){

            Route::get('index', 'ProductController@index')->name('products.index');

            Route::get('create', 'ProductController@create')->name('products.create');

            Route::post('store', 'ProductController@store')->name('products.store');

            Route::get('edit/{product_id}', 'ProductController@edit')->name('products.edit');

            Route::post('update/{product_id}', 'ProductController@update')->name('products.update');

            Route::post('delete', 'ProductController@destroy')->name('products.destroy');

        });

        Route::group(['prefix' => 'trim'], function(){

            Route::get('index', 'TrimController@index')->name('trims.index');

            Route::get('create', 'TrimController@create')->name('trims.create');

            Route::post('store', 'TrimController@store')->name('trims.store');

            Route::get('edit/{trim_id}', 'TrimController@edit')->name('trims.edit');

            Route::post('update/{trim_id}', 'TrimController@update')->name('trims.update');

            Route::post('delete', 'TrimController@destroy')->name('trims.destroy');

        });

        Route::group(['prefix' => 'query'], function(){

            Route::get('index', 'QueryController@index')->name('queries.index');

            Route::post('change-status', 'QueryController@changeStatus')->name('queries.change_status');

            Route::get('history/{query_id}', 'QueryController@history')->name('queries.history');

            Route::get('create', 'QueryController@create')->name('queries.create');

            Route::post('store', 'QueryController@store')->name('queries.store');

            Route::get('show/{query_id}', 'QueryController@show')->name('queries.show');

            Route::get('edit/{query_id}', 'QueryController@edit')->name('queries.edit');

            Route::post('update/{query_id}', 'QueryController@update')->name('queries.update');

            Route::post('delete', 'QueryController@destroy')->name('queries.destroy');

        });

        Route::group(['prefix' => 'expense-category'], function(){

            Route::get('index', 'ExpenseCategoryController@index')->name('expense_categories.index');

            Route::get('create', 'ExpenseCategoryController@create')->name('expense_categories.create');
            
            Route::post('store', 'ExpenseCategoryController@store')->name('expense_categories.store');

            Route::get('edit/{expense_category_id}', 'ExpenseCategoryController@edit')->name('expense_categories.edit');

            Route::post('update/{expense_category_id}', 'ExpenseCategoryController@update')->name('expense_categories.update');

            Route::post('delete', 'ExpenseCategoryController@destroy')->name('expense_categories.destroy');

        });


        Route::group(['prefix' => 'expense'], function(){

            Route::get('index', 'ExpenseController@index')->name('expenses.index');

            Route::get('create', 'ExpenseController@create')->name('expenses.create');

            Route::post('store', 'ExpenseController@store')->name('expenses.store');

            Route::get('edit/{expense_id}', 'ExpenseController@edit')->name('expenses.edit');

            Route::post('update/{expense_id}', 'ExpenseController@update')->name('expenses.update');

            Route::get('pending', 'ExpenseController@pending')->name('expenses.pending');

            Route::get('pending-payments/{expense_id}', 'ExpenseController@pendingPayments')->name('expenses.pending_payments');

            Route::get('view-payment/{expense_id}', 'ExpenseController@approvedPayments')->name('expenses.approved_payments');

            Route::get('expense-payment/{expense_id}', 'ExpenseController@payment')->name('expenses.payment');

            Route::post('store-payment/{expense_id}', 'ExpenseController@storePayment')->name('expenses.storePayment');

            Route::get('edit-payment/{expense_id}', 'ExpenseController@editPayment')->name('expenses.edit_payment');

            Route::post('update-payment/{expense_id}', 'ExpenseController@updatePayment')->name('expenses.update_payment');

            Route::post('delete', 'ExpenseController@destroy')->name('expenses.destroy');

            Route::post('delete-payment', 'ExpenseController@destroyPayment')->name('expenses.destroy_payment');

            Route::get('print-voucher/{expense_id}', 'ExpenseController@printVoucher')->name('expenses.print_voucher');

        });

        
        Route::group(['prefix' => 'revenue-category'], function(){
        
            Route::get('index', 'RevenueCategoryController@index')->name('revenue_categories.index');
        
            Route::get('create', 'RevenueCategoryController@create')->name('revenue_categories.create');

            Route::get('edit/{revenue_category_id}', 'RevenueCategoryController@edit')->name('revenue_categories.edit');

            Route::post('store', 'RevenueCategoryController@store')->name('revenue_categories.store');

            Route::post('update/{revenue_category_id}', 'RevenueCategoryController@update')->name('revenue_categories.update');

        });


        Route::group(['prefix' => 'revenue'], function(){

            Route::get('index', 'RevenueController@index')->name('revenues.index');

            Route::get('create', 'RevenueController@create')->name('revenues.create');

            Route::post('store', 'RevenueController@store')->name('revenues.store');

            Route::get('edit/{revenue_id}', 'RevenueController@edit')->name('revenues.edit');

            Route::post('update/{revenue_id}', 'RevenueController@update')->name('revenues.update');

            Route::get('pending', 'RevenueController@pending')->name('revenues.pending');

            Route::get('pending-payments/{revenue_id}', 'RevenueController@pendingPayments')->name('revenues.pending_payments');

            Route::get('view-payment/{revenue_id}', 'RevenueController@approvedPayments')->name('revenues.approved_payments');

            Route::get('revenue-payment/{revenue_id}', 'RevenueController@payment')->name('revenues.payment');

            Route::post('store-payment/{revenue_id}', 'RevenueController@storePayment')->name('revenues.storePayment');

            Route::get('edit-payment/{expense_id}', 'RevenueController@editPayment')->name('revenues.edit_payment');

            Route::post('update-payment/{expense_id}', 'RevenueController@updatePayment')->name('revenues.update_payment');

            Route::post('delete', 'RevenueController@destroy')->name('revenues.destroy');

            Route::post('delete-payment', 'RevenueController@destroyPayment')->name('revenues.destroy_payment');

        });


        Route::group(['prefix' => 'account-category'], function(){
            
            Route::get('index', 'AccountCategoryController@index')->name('account_categories.index');
            
            Route::get('create', 'AccountCategoryController@create')->name('account_categories.create');
        
            Route::get('edit/{id}', 'AccountCategoryController@edit')->name('account_categories.edit');

            Route::post('store', 'AccountCategoryController@store')->name('account_categories.store');

            Route::post('update/{id}', 'AccountCategoryController@update')->name('account_categories.update');

        });


        Route::group(['prefix' => 'account'], function(){
                
            Route::get('index', 'AccountController@index')->name('accounts.index');
            
            Route::get('create', 'AccountController@create')->name('accounts.create');
        
            Route::get('edit/{id}', 'AccountController@edit')->name('accounts.edit');

            Route::post('store', 'AccountController@store')->name('accounts.store');

            Route::post('update/{id}', 'AccountController@update')->name('accounts.update');

            Route::get('get-accounts-by-warehouse', 'AccountController@getAccountsByWarehouse')->name('accounts.get_accounts_by_warehouse');

            Route::get('current-balance', 'AccountController@getCurrentBalance')->name('accounts.balance');

        });

        
        Route::group(['prefix' => 'account-statement'], function(){
                
            Route::get('index', 'AccountStatementController@index')->name('account_statements.index');
            
            Route::get('balance-sheet', 'AccountStatementController@balanceSheet')->name('account_statements.balance_sheet');

        });


        Route::group(['prefix' => 'cash-in-cash-out'], function(){

            Route::get('index', 'CashInCashOutController@index')->name('cash_in_cash_outs.index');

            Route::get('add-money', 'CashInCashOutController@addMoney')->name('add_money.create');

            Route::post('add-money/store', 'CashInCashOutController@addMoneyStore')->name('add_money.store');            
            
            Route::get('withdraw-money', 'CashInCashOutController@withdrawMoney')->name('withdraw_money.create');

            Route::post('withdraw-money/store', 'CashInCashOutController@withdrawMoneyStore')->name('withdraw_money.store');

            Route::get('edit/{id}', 'CashInCashOutController@edit')->name('cash_in_cash_outs.edit');

            Route::post('store', 'CashInCashOutController@store')->name('cash_in_cash_outs.store');

            Route::post('update/{id}', 'CashInCashOutController@update')->name('cash_in_cash_outs.update');

            Route::post('delete', 'CashInCashOutController@destroy')->name('cash_in_cash_outs.destroy');

        });


         Route::group(['prefix' => 'money-transfer'], function(){

            Route::get('index', 'MoneyTransferController@index')->name('money_transfers.index');

            Route::get('create', 'MoneyTransferController@create')->name('money_transfers.create');

            Route::get('edit/{id}', 'MoneyTransferController@edit')->name('money_transfers.edit');

            Route::post('store', 'MoneyTransferController@store')->name('money_transfers.store');

            Route::post('update/{id}', 'MoneyTransferController@update')->name('money_transfers.update');

            Route::get('pending', 'MoneyTransferController@pending')->name('money_transfers.pending');

            Route::post('delete', 'MoneyTransferController@destroy')->name('money_transfers.destroy');
        
        });

        
        Route::group(['prefix' => 'loan-client'], function(){

            Route::get('index', 'LoanClientController@index')->name('loan_clients.index');

            Route::get('create', 'LoanClientController@create')->name('loan_clients.create');

            Route::get('{loan_client_id}', 'LoanClientController@show')->name('loan_clients.show');

            Route::get('edit/{loan_client_id}', 'LoanClientController@edit')->name('loan_clients.edit');

            Route::post('store', 'LoanClientController@store')->name('loan_clients.store');

            Route::post('update/{loan_client_id}', 'LoanClientController@update')->name('loan_clients.update');

        });


        Route::group(['prefix' => 'loan'], function(){

            Route::get('index', 'LoanController@index')->name('loans.index');

            Route::get('create', 'LoanController@create')->name('loans.create');

            Route::get('edit/{loan_id}', 'LoanController@edit')->name('loans.edit');

            Route::post('store', 'LoanController@store')->name('loans.store');

            Route::post('update/{loan_id}', 'LoanController@update')->name('loans.update');

            Route::get('pending', 'LoanController@pending')->name('loans.pending');

            Route::get('pending-paybacks/{loan_id}', 'LoanController@pendingPaybacks')->name('loans.pending_paybacks');

            Route::get('view-paybacks/{loan_id}', 'LoanController@approvedPaybacks')->name('loans.approved_paybacks');

            Route::get('paybacks/{loan_id}', 'LoanController@payback')->name('loans.payback');

            Route::post('store-paybacks/{loan_id}', 'LoanController@storePayback')->name('loans.storePayback');

        });


        Route::group(['prefix' => 'investor'], function(){

            Route::get('index', 'InvestorController@index')->name('investors.index');

            Route::get('create', 'InvestorController@create')->name('investors.create');

            Route::get('{investor_id}', 'InvestorController@show')->name('investors.show');

            Route::get('edit/{investor_id}', 'InvestorController@edit')->name('investors.edit');

            Route::post('store', 'InvestorController@store')->name('investors.store');

            Route::post('update/{investor_id}', 'InvestorController@update')->name('investors.update');

        });


        Route::group(['prefix' => 'investment'], function(){

            Route::get('index', 'InvestmentController@index')->name('investments.index');

            Route::get('create', 'InvestmentController@create')->name('investments.create');

            Route::get('edit/{investment_id}', 'InvestmentController@edit')->name('investments.edit');

            Route::post('store', 'InvestmentController@store')->name('investments.store');

            Route::post('update/{investment_id}', 'InvestmentController@update')->name('investments.update');

            Route::get('pending', 'InvestmentController@pending')->name('investments.pending');

            Route::get('pending-returns/{investment_id}', 'InvestmentController@pendingReturns')->name('investments.pending_returns');

            Route::get('view-returns/{investment_id}', 'InvestmentController@approvedReturns')->name('investments.approved_returns');

            Route::get('returns/{investment_id}', 'InvestmentController@return')->name('investments.return');

            Route::post('store-returns/{investment_id}', 'InvestmentController@storeReturn')->name('investments.storeReturn');

        });


        Route::group(['prefix' => 'transaction-pool'], function(){

            Route::get('index', 'TransactionPoolController@index')->name('transaction_pools.index');

            Route::get('check', 'TransactionPoolController@check')->name('transaction_pools.check');

            Route::get('approve/{transaction_pool_id}', 'TransactionPoolController@approve')->name('transaction_pools.approve');

            Route::get('reject/{transaction_pool_id}', 'TransactionPoolController@reject')->name('transaction_pools.reject');

        });


        Route::group(['prefix' => 'report'], function(){

            Route::get('index', 'ReportController@index')->name('reports.index');

        });


        Route::group(['prefix' => 'settings'], function(){

            Route::get('index', 'SettingController@index')->name('settings.index');

            Route::post('store', 'SettingController@store')->name('settings.store');

            Route::get('deploy', 'SettingController@deploy')->name('settings.deploy');

        });

        
        Route::group(['prefix' => 'warehouse'], function(){

            Route::get('index', 'WarehouseController@index')->name('warehouses.index');

            Route::get('create', 'WarehouseController@create')->name('warehouses.create');

            Route::post('store', 'WarehouseController@store')->name('warehouses.store');

            Route::get('edit/{warehouse_id}', 'WarehouseController@edit')->name('warehouses.edit');

            Route::post('update/{warehouse_id}', 'WarehouseController@update')->name('warehouses.update');
            
        });


        Route::group(['prefix' => 'area'], function(){

            Route::get('index', 'AreaController@index')->name('areas.index');

            Route::get('create', 'AreaController@create')->name('areas.create');

            Route::post('store', 'AreaController@store')->name('areas.store');

            Route::get('edit/{area_id}', 'AreaController@edit')->name('areas.edit');

            Route::post('update/{area_id}', 'AreaController@update')->name('areas.update');
            
        });

        
        Route::group(['prefix' => 'brand'], function(){

            Route::get('index', 'BrandController@index')->name('brands.index');

            Route::get('create', 'BrandController@create')->name('brands.create');

            Route::post('store', 'BrandController@store')->name('brands.store');

            Route::get('edit/{brand_id}', 'BrandController@edit')->name('brands.edit');

            Route::post('update/{brand_id}', 'BrandController@update')->name('brands.update');
            
        });


        Route::group(['prefix' => 'reference'], function(){
                
            Route::get('index', 'ReferenceController@index')->name('references.index');

            Route::get('create', 'ReferenceController@create')->name('references.create');

            Route::post('store', 'ReferenceController@store')->name('references.store');

            Route::get('edit/{reference_id}', 'ReferenceController@edit')->name('references.edit');

            Route::post('update/{reference_id}', 'ReferenceController@update')->name('references.update');

        });


        Route::group(['prefix' => 'tax'], function(){

            Route::get('index', 'TaxController@index')->name('taxes.index');

            Route::get('create', 'TaxController@create')->name('taxes.create');

            Route::post('store', 'TaxController@store')->name('taxes.store');

            Route::get('edit/{tax_id}', 'TaxController@edit')->name('taxes.edit');

            Route::post('update/{tax_id}', 'TaxController@update')->name('taxes.update');
            
        });


        Route::group(['prefix' => 'sms'], function(){

            Route::get('index', 'SmsController@index')->name('sms.index');

            Route::get('create', 'SmsController@create')->name('sms.create');

            Route::post('store', 'SmsController@store')->name('sms.store');
            
        });

        Route::get('change-warehouse/{warehouse_id}', 'WarehouseController@changeWarehouse')->name('change_warehouse');

    });
    
});
