<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Payroll;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Department;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PayrollDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRouteTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        Auth::guard('admin')->login($admin);

        $permissions = Permission::all();

        $permissions = $permissions->pluck('name')->toArray();

        $warehouses = Warehouse::all();

        // $this->app['session']->put('user_permissions', $permissions);

        session(['user_permissions' => $permissions]);

        if($warehouses->count() > 0)
        {
            // $this->app['session']->put('user_warehouse', $warehouses[0]);

            session(['user_warehouse' => $warehouses[0]]);

            // $this->app['session']->put('user_warehouses', $warehouses);

            session(['user_warehouses' => $warehouses]);
        }

        // Users

        $response = $this->get(route('users.index'));
        $response->assertStatus(200, 'The users index route is not working');

        $response = $this->get(route('users.create'));
        $response->assertStatus(200, 'The users create route is not working');

        $users = User::select('id')->get();

        foreach ($users as $user) {
            $response = $this->get(route('users.show', $user->id));
            $response->assertStatus(200, 'The users show route is not working');

            $response = $this->get(route('users.edit', $user->id));
            $response->assertStatus(200, 'The users edit route is not working');

            $response = $this->get(route('users.user_roles', $user->id));
            $response->assertStatus(200, 'The users user_roles route is not working');
        }

        // Roles

        $response = $this->get(route('roles.index'));
        $response->assertStatus(200, 'The roles index route is not working');

        $response = $this->get(route('roles.create'));
        $response->assertStatus(200, 'The roles create route is not working');

        $roles = Role::select('id')->get();

        foreach ($roles as $role) {

            $response = $this->get(route('roles.edit', $role->id));
            $response->assertStatus(200, 'The roles edit route is not working');

            $response = $this->get(route('roles.role_permissions', $role->id));
            $response->assertStatus(200, 'The roles role_permissions route is not working');
        }

        // Department

        $response = $this->get(route('departments.index'));
        $response->assertStatus(200, 'The departments index route is not working');

        $response = $this->get(route('departments.create'));
        $response->assertStatus(200, 'The departments create route is not working');

        $departments = Department::select('id')->get();

        foreach ($departments as $department) {

            $response = $this->get(route('departments.edit', $department->id));
            $response->assertStatus(200, 'The departments edit route is not working');
        }

        // Employee

        $response = $this->get(route('employees.index'));
        $response->assertStatus(200, 'The employees index route is not working');

        $response = $this->get(route('employees.create'));
        $response->assertStatus(200, 'The employees create route is not working');

        $employees = Employee::select('id')->get();

        foreach ($employees as $employee) {
            $response = $this->get(route('employees.show', $employee->id));
            $response->assertStatus(200, 'The employees show route is not working');

            $response = $this->get(route('employees.edit', $employee->id));
            $response->assertStatus(200, 'The employees edit route is not working');

            $response = $this->get(route('employees.print', $employee->id));
            $response->assertStatus(200, 'The employees print route is not working');

            $response = $this->get(route('employees.create_salary_structure', $employee->id));
            $response->assertStatus(200, 'The employees create_salary_structure route is not working');
        }

        // Salary Sheet

        $response = $this->get(route('payrolls.index'));
        $response->assertStatus(200, 'The payrolls index route is not working');

        $response = $this->get(route('payrolls.create'));
        $response->assertStatus(200, 'The payrolls create route is not working');

        $payrolls = Payroll::select('id')->get();

        foreach ($payrolls as $payroll) {
            $response = $this->get(route('payrolls.show', $payroll->id));
            $response->assertStatus(200, 'The payrolls show route is not working');

            $response = $this->get(route('payrolls.payroll_details', $payroll->id));
            $response->assertStatus(200, 'The payrolls payroll_details route is not working');
        }

        // Payroll

        $response = $this->get(route('payrolls.payroll.index'));
        $response->assertStatus(200, 'The payrolls payroll index route is not working');

        $payrolls = PayrollDetail::select('id')->get();

        foreach ($payrolls as $payroll) {
            $response = $this->get(route('payrolls.payroll.print', $payroll->id));
            $response->assertStatus(200, 'The payrolls payroll print route is not working');
        }


        // Customer

        $response = $this->get(route('customers.index'));
        $response->assertStatus(200, 'The customers index route is not working');

        $response = $this->get(route('customers.create'));
        $response->assertStatus(200, 'The customers create route is not working');

        $response = $this->get(route('customers.due_report'));
        $response->assertStatus(200, 'The customers due_report route is not working');

        $customers = Customer::select('id')->get();

        foreach ($customers as $customer) {
            $response = $this->get(route('customers.show', $customer->id));
            $response->assertStatus(200, 'The customers show route is not working');

            $response = $this->get(route('customers.edit', $customer->id));
            $response->assertStatus(200, 'The customers edit route is not working');

            $response = $this->get(route('customers.history', $customer->id));
            $response->assertStatus(200, 'The customers history route is not working');

            $response = $this->get(route('customers.transaction_history', $customer->id));
            $response->assertStatus(200, 'The customers transaction_history route is not working');
        }

        // Supplier

        $response = $this->get(route('suppliers.index'));
        $response->assertStatus(200, 'The suppliers index route is not working');

        $response = $this->get(route('suppliers.create'));
        $response->assertStatus(200, 'The suppliers create route is not working');

        $response = $this->get(route('suppliers.due_report'));
        $response->assertStatus(200, 'The suppliers due_report route is not working');

        $suppliers = Supplier::select('id')->get();

        foreach ($suppliers as $supplier) {
            $response = $this->get(route('suppliers.show', $supplier->id));
            $response->assertStatus(200, 'The suppliers show route is not working');

            $response = $this->get(route('suppliers.edit', $supplier->id));
            $response->assertStatus(200, 'The suppliers edit route is not working');

            $response = $this->get(route('suppliers.history', $supplier->id));
            $response->assertStatus(200, 'The suppliers history route is not working');

            $response = $this->get(route('suppliers.transaction_history', $supplier->id));
            $response->assertStatus(200, 'The suppliers transaction_history route is not working');
        }

    }
}
