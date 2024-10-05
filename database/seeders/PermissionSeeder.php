<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PERMISSION GROUP - DASHBOARD

        $permission = Permission::where('name', '=', 'dashboard.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "dashboard.index";
            $permission->alias = "View Dashboard Cards";
            $permission->description = "To view the dashboard cards";
            $permission->permission_group = "Dashboard";

            $permission->save();
        }

        // PERMISSION GROUP - USER

        $permission = Permission::where('name', '=', 'user.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "user.index";
            $permission->alias = "View Users";
            $permission->description = "To view the list of users";
            $permission->permission_group = "User";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'user.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "user.create";
            $permission->alias = "Create Users";
            $permission->description = "To create a new user";
            $permission->permission_group = "User";

            $permission->save();
        }


        $permission = Permission::where('name', '=', 'user.assign_role')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "user.assign_role";
            $permission->alias = "Assign Roles";
            $permission->description = "To assign roles to users";
            $permission->permission_group = "User";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'user.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "user.edit";
            $permission->alias = "Edit Users";
            $permission->description = "To edit a user";
            $permission->permission_group = "User";

            $permission->save();
        }

        // PERMISSION GROUP - ROLE

        $permission = Permission::where('name', '=', 'role.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "role.index";
            $permission->alias = "View Roles";
            $permission->description = "To view the list of roles";
            $permission->permission_group = "User";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'role.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "role.create";
            $permission->alias = "Create Roles";
            $permission->description = "To create a new role";
            $permission->permission_group = "User";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'role.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "role.edit";
            $permission->alias = "Edit Roles";
            $permission->description = "To edit a role";
            $permission->permission_group = "User";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'role.assign_permission')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "role.assign_permission";
            $permission->alias = "Assign Permissions";
            $permission->description = "To assign permissions to roles";
            $permission->permission_group = "User";

            $permission->save();
        }

        // PERMISSION GROUP - HRM

        $permission = Permission::where('name', '=', 'department.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "department.index";
            $permission->alias = "View Departments";
            $permission->description = "To view the list of departments";
            $permission->permission_group = "HRM";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'department.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "department.create";
            $permission->alias = "Create Departments";
            $permission->description = "To create a new department";
            $permission->permission_group = "HRM";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'department.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "department.edit";
            $permission->alias = "Edit Departments";
            $permission->description = "To edit a department";
            $permission->permission_group = "HRM";

            $permission->save();
        }


        $permission = Permission::where('name', '=', 'employee.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "employee.index";
            $permission->alias = "View Employees";
            $permission->description = "To view the list of employees";
            $permission->permission_group = "HRM";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'employee.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "employee.create";
            $permission->alias = "Create Employees";
            $permission->description = "To create a new employee";
            $permission->permission_group = "HRM";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'employee.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "employee.edit";
            $permission->alias = "Edit Employees";
            $permission->description = "To edit an employee";
            $permission->permission_group = "HRM";

            $permission->save();
        }
        
        $permission = Permission::where('name', '=', 'employee.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "employee.delete";
            $permission->alias = "Delete Employees";
            $permission->description = "To delete an employee";
            $permission->permission_group = "HRM";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'employee.print')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "employee.print";
            $permission->alias = "Print Employee Details";
            $permission->description = "To print the details of an employee";
            $permission->permission_group = "HRM";

            $permission->save();
        }

        // $permission = Permission::where('name', '=', 'job_duration.toggle')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "job_duration.toggle";
        //     $permission->alias = "Toggle Job Duration";
        //     $permission->description = "To toggle job duration of an employee";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'salary_structure.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "salary_structure.index";
        //     $permission->alias = "View Salary Structure";
        //     $permission->description = "To view the list of salary structures";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'salary_structure.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "salary_structure.create";
        //     $permission->alias = "Create Salary Structure";
        //     $permission->description = "To create a new salary structure";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'salary_sheet.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "salary_sheet.index";
        //     $permission->alias = "View Salary Sheets";
        //     $permission->description = "To view the list of monthly salary sheets";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'salary_sheet.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "salary_sheet.create";
        //     $permission->alias = "Create Salary Sheet";
        //     $permission->description = "To create a new monthly salary sheet";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'salary_sheet.payroll_details')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "salary_sheet.payroll_details";
        //     $permission->alias = "View Salary Sheet Details";
        //     $permission->description = "To view the details of a monthly salary sheet";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'payroll.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "payroll.index";
        //     $permission->alias = "View Salary Sheet of an Employee";
        //     $permission->description = "To view the list of monthly salary sheets of Employees";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }
        
        // $permission = Permission::where('name', '=', 'payroll.payroll.disburse')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "payroll.payroll.disburse";
        //     $permission->alias = "Disburse Salary Sheet of Employees";
        //     $permission->description = "To disburse the monthly salary sheet of Employees";
        //     $permission->permission_group = "HRM";

        //     $permission->save();
        // }


        $permission = Permission::where('name', '=', 'buyer.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "buyer.index";
            $permission->alias = "View Buyers";
            $permission->description = "To view the list of buyers";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'buyer.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "buyer.create";
            $permission->alias = "Create Buyers";
            $permission->description = "To create a new buyer";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'buyer.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "buyer.edit";
            $permission->alias = "Edit Buyers";
            $permission->description = "To edit a buyer";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'buyer.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "buyer.delete";
            $permission->alias = "Delete Buyers";
            $permission->description = "To delete a buyer";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'buyer.show')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "buyer.show";
            $permission->alias = "View Buyer Details";
            $permission->description = "To view the details of a buyer";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'buyer.add_contact_person')->first();
        
        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "buyer.add_contact_person";
            $permission->alias = "Add Contact Person";
            $permission->description = "To add a new contact person to a buyer";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name', '=', 'buyer.edit_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "buyer.edit_contact_person";
            $permission->alias = "Edit Contact Person";
            $permission->description = "To edit a contact person of a buyer";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name', '=', 'buyer.delete_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "buyer.delete_contact_person";
            $permission->alias = "Delete Contact Person";
            $permission->description = "To delete a contact person of a buyer";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name', '=', 'brand.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "brand.index";
            $permission->alias = "View Brands";
            $permission->description = "To view the list of brands";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'brand.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "brand.create";
            $permission->alias = "Create Brands";
            $permission->description = "To create a new brand";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'brand.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "brand.edit";
            $permission->alias = "Edit Brands";
            $permission->description = "To edit a brand";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'brand.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "brand.delete";
            $permission->alias = "Delete Brands";
            $permission->description = "To delete a brand";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'brand.show')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "brand.show";
            $permission->alias = "View Brand Details";
            $permission->description = "To view the details of a brand";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.index')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.index";
            $permission->alias = "View Factories";
            $permission->description = "To view the list of factories";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.create')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.create";
            $permission->alias = "Create Factories";
            $permission->description = "To create a new factory";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.edit";
            $permission->alias = "Edit Factories";
            $permission->description = "To edit a factory";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.delete";
            $permission->alias = "Delete Factories";
            $permission->description = "To delete a factory";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.show')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.show";
            $permission->alias = "View Factory Details";
            $permission->description = "To view the details of a factory";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.add_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.add_contact_person";
            $permission->alias = "Add Contact Person";
            $permission->description = "To add a new contact person to a factory";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.edit_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.edit_contact_person";
            $permission->alias = "Edit Contact Person";
            $permission->description = "To edit a contact person of a factory";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'factory.delete_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "factory.delete_contact_person";
            $permission->alias = "Delete Contact Person";
            $permission->description = "To delete a contact person of a factory";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.index')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.index";
            $permission->alias = "View Suppliers";
            $permission->description = "To view the list of suppliers";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.create')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.create";
            $permission->alias = "Create Suppliers";
            $permission->description = "To create a new supplier";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.edit";
            $permission->alias = "Edit Suppliers";
            $permission->description = "To edit a supplier";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.delete";
            $permission->alias = "Delete Suppliers";
            $permission->description = "To delete a supplier";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.show')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.show";
            $permission->alias = "View Supplier Details";
            $permission->description = "To view the details of a supplier";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.add_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.add_contact_person";
            $permission->alias = "Add Contact Person";
            $permission->description = "To add a new contact person to a supplier";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.edit_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.edit_contact_person";
            $permission->alias = "Edit Contact Person";
            $permission->description = "To edit a contact person of a supplier";
            $permission->permission_group = "Config";
            $permission->save();
        }

        $permission = Permission::where('name' , '=' , 'supplier.delete_contact_person')->first();

        if($permission == null)
        {
            $permission = new Permission();
            $permission->name = "supplier.delete_contact_person";
            $permission->alias = "Delete Contact Person";
            $permission->description = "To delete a contact person of a supplier";
            $permission->permission_group = "Config";
            $permission->save();
        }
        
        $permission = Permission::where('name', '=', 'product_type.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product_type.index";
            $permission->alias = "View Product Types";
            $permission->description = "To view the list of product types";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product_type.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product_type.create";
            $permission->alias = "Create Product Types";
            $permission->description = "To create a new product type";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product_type.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product_type.edit";
            $permission->alias = "Edit Product Types";
            $permission->description = "To edit a product type";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product_type.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product_type.delete";
            $permission->alias = "Delete Product Types";
            $permission->description = "To delete a product type";
            $permission->permission_group = "Config";

            $permission->save();
        }


        // $permission = Permission::where('name', '=', 'client_source.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_source.index";
        //     $permission->alias = "View Client Sources";
        //     $permission->description = "To view the list of client sources";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client_source.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_source.create";
        //     $permission->alias = "Create Client Sources";
        //     $permission->description = "To create a new client source";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client_source.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_source.edit";
        //     $permission->alias = "Edit Client Sources";
        //     $permission->description = "To edit a client source";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client_source.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_source.delete";
        //     $permission->alias = "Delete Client Sources";
        //     $permission->description = "To delete a client source";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client_status.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_status.index";
        //     $permission->alias = "View Client Statuses";
        //     $permission->description = "To view the list of client statuses";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client_status.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_status.create";
        //     $permission->alias = "Create Client Statuses";
        //     $permission->description = "To create a new client status";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client_status.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_status.edit";
        //     $permission->alias = "Edit Client Statuses";
        //     $permission->description = "To edit a client status";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client_status.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client_status.delete";
        //     $permission->alias = "Delete Client Statuses";
        //     $permission->description = "To delete a client status";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'business_category.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "business_category.index";
        //     $permission->alias = "View Business Categories";
        //     $permission->description = "To view the list of business categories";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'business_category.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "business_category.create";
        //     $permission->alias = "Create Business Categories";
        //     $permission->description = "To create a new business category";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'business_category.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "business_category.edit";
        //     $permission->alias = "Edit Business Categories";
        //     $permission->description = "To edit a business category";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'business_category.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "business_category.delete";
        //     $permission->alias = "Delete Business Categories";
        //     $permission->description = "To delete a business category";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'interested_in.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "interested_in.index";
        //     $permission->alias = "View Interests";
        //     $permission->description = "To view the list of interests";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'interested_in.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "interested_in.create";
        //     $permission->alias = "Create Interests";
        //     $permission->description = "To create a new interest";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'interested_in.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "interested_in.edit";
        //     $permission->alias = "Edit Interests";
        //     $permission->description = "To edit an interest";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'interested_in.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "interested_in.delete";
        //     $permission->alias = "Delete Interests";
        //     $permission->description = "To delete an interest";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client.index";
        //     $permission->alias = "View Clients";
        //     $permission->description = "To view the list of clients";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client.create";
        //     $permission->alias = "Create Clients";
        //     $permission->description = "To create a new client";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client.edit";
        //     $permission->alias = "Edit Clients";
        //     $permission->description = "To edit a client";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client.delete";
        //     $permission->alias = "Delete Clients";
        //     $permission->description = "To delete a client";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client.show')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "client.show";
        //     $permission->alias = "View Client Details";
        //     $permission->description = "To view the details of a client";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'client.add_contact_person')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();
        //     $permission->name = "client.add_contact_person";
        //     $permission->alias = "Add Contact Person";
        //     $permission->description = "To add a new contact person to a client";
        //     $permission->permission_group = "Config";
        //     $permission->save();
        // }
        
        
        // // PERMISSION GROUP - CALL

        // $permission = Permission::where('name', '=', 'call_type.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_type.index";
        //     $permission->alias = "View Call Types";
        //     $permission->description = "To view the list of call types";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call_type.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_type.create";
        //     $permission->alias = "Create Call Types";
        //     $permission->description = "To create a new call type";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }
        
        // $permission = Permission::where('name', '=', 'call_type.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_type.edit";
        //     $permission->alias = "Edit Call Types";
        //     $permission->description = "To edit a call type";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call_type.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_type.delete";
        //     $permission->alias = "Delete Call Types";
        //     $permission->description = "To delete a call type";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call_status.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_status.index";
        //     $permission->alias = "View Call Statuses";
        //     $permission->description = "To view the list of call statuses";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call_status.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_status.create";
        //     $permission->alias = "Create Call Statuses";
        //     $permission->description = "To create a new call status";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call_status.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_status.edit";
        //     $permission->alias = "Edit Call Statuses";
        //     $permission->description = "To edit a call status";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call_status.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call_status.delete";
        //     $permission->alias = "Delete Call Statuses";
        //     $permission->description = "To delete a call status";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call.index";
        //     $permission->alias = "View Calls";
        //     $permission->description = "To view the list of calls";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call.create";
        //     $permission->alias = "Create Calls";
        //     $permission->description = "To create a new call";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call.edit";
        //     $permission->alias = "Edit Calls";
        //     $permission->description = "To edit a call";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'call.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "call.delete";
        //     $permission->alias = "Delete Calls";
        //     $permission->description = "To delete a call";
        //     $permission->permission_group = "Call";

        //     $permission->save();
        // }

        // // PERMISSION GROUP - MEETING

        // $permission = Permission::where('name', '=', 'meeting_type.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_type.index";
        //     $permission->alias = "View Meeting Types";
        //     $permission->description = "To view the list of meeting types";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_type.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_type.create";
        //     $permission->alias = "Create Meeting Types";
        //     $permission->description = "To create a new meeting type";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_type.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_type.edit";
        //     $permission->alias = "Edit Meeting Types";
        //     $permission->description = "To edit a meeting type";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_type.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_type.delete";
        //     $permission->alias = "Delete Meeting Types";
        //     $permission->description = "To delete a meeting type";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_title.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_title.index";
        //     $permission->alias = "View Meeting Titles";
        //     $permission->description = "To view the list of meeting titles";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_title.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_title.create";
        //     $permission->alias = "Create Meeting Titles";
        //     $permission->description = "To create a new meeting title";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_title.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_title.edit";
        //     $permission->alias = "Edit Meeting Titles";
        //     $permission->description = "To edit a meeting title";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_title.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_title.delete";
        //     $permission->alias = "Delete Meeting Titles";
        //     $permission->description = "To delete a meeting title";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_status.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_status.index";
        //     $permission->alias = "View Meeting Statuses";
        //     $permission->description = "To view the list of meeting statuses";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_status.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_status.create";
        //     $permission->alias = "Create Meeting Statuses";
        //     $permission->description = "To create a new meeting status";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_status.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_status.edit";
        //     $permission->alias = "Edit Meeting Statuses";
        //     $permission->description = "To edit a meeting status";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting_status.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting_status.delete";
        //     $permission->alias = "Delete Meeting Statuses";
        //     $permission->description = "To delete a meeting status";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.index";
        //     $permission->alias = "View Meetings";
        //     $permission->description = "To view the list of meetings";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.create";
        //     $permission->alias = "Create Meetings";
        //     $permission->description = "To create a new meeting";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.edit";
        //     $permission->alias = "Edit Meetings";
        //     $permission->description = "To edit a meeting";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.delete";
        //     $permission->alias = "Delete Meetings";
        //     $permission->description = "To delete a meeting";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting.view_minutes')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.view_minutes";
        //     $permission->alias = "View Meeting Minutes";
        //     $permission->description = "To view the minutes of a meeting";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }
        
        // $permission = Permission::where('name', '=', 'meeting.add_minutes')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.add_minutes";
        //     $permission->alias = "Add Meeting Minutes";
        //     $permission->description = "To add minutes to a meeting";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting.edit_minutes')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.edit_minutes";
        //     $permission->alias = "Edit Meeting Minutes";
        //     $permission->description = "To edit the minutes of a meeting";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'meeting.delete_minutes')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "meeting.delete_minutes";
        //     $permission->alias = "Delete Meeting Minutes";
        //     $permission->description = "To delete the minutes of a meeting";
        //     $permission->permission_group = "Meeting";

        //     $permission->save();
        // }


        // // PERMISSION GROUP - PROJECT

        // $permission = Permission::where('name', '=', 'project_status.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_status.index";
        //     $permission->alias = "View Project Statuses";
        //     $permission->description = "To view the list of project statuses";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project_status.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_status.create";
        //     $permission->alias = "Create Project Statuses";
        //     $permission->description = "To create a new project status";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project_status.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_status.edit";
        //     $permission->alias = "Edit Project Statuses";
        //     $permission->description = "To edit a project status";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project_status.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_status.delete";
        //     $permission->alias = "Delete Project Statuses";
        //     $permission->description = "To delete a project status";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project_type.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_type.index";
        //     $permission->alias = "View Project Types";
        //     $permission->description = "To view the list of project types";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project_type.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_type.create";
        //     $permission->alias = "Create Project Types";
        //     $permission->description = "To create a new project type";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project_type.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_type.edit";
        //     $permission->alias = "Edit Project Types";
        //     $permission->description = "To edit a project type";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project_type.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project_type.delete";
        //     $permission->alias = "Delete Project Types";
        //     $permission->description = "To delete a project type";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.index";
        //     $permission->alias = "View Projects";
        //     $permission->description = "To view the list of projects";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.create";
        //     $permission->alias = "Create Projects";
        //     $permission->description = "To create a new project";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.edit";
        //     $permission->alias = "Edit Projects";
        //     $permission->description = "To edit a project";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.delete";
        //     $permission->alias = "Delete Projects";
        //     $permission->description = "To delete a project";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.show')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.show";
        //     $permission->alias = "View Project Details";
        //     $permission->description = "To view the details of a project";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.payment.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.payment.index";
        //     $permission->alias = "View Project Payments";
        //     $permission->description = "To view the list of project payments";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.payment.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.payment.create";
        //     $permission->alias = "Create Project Payments";
        //     $permission->description = "To create a new project payment";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.payment.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.payment.edit";
        //     $permission->alias = "Edit Project Payments";
        //     $permission->description = "To edit a project payment";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'project.payment.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "project.payment.delete";
        //     $permission->alias = "Delete Project Payments";
        //     $permission->description = "To delete a project payment";
        //     $permission->permission_group = "Project";

        //     $permission->save();
        // }
         
        // PERMISSION GROUP - QUERY

        
        $permission = Permission::where('name', '=', 'product_type.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product_type.create";
            $permission->alias = "Create Product Types";
            $permission->description = "To create a new product type";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product_type.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product_type.edit";
            $permission->alias = "Edit Product Types";
            $permission->description = "To edit a product type";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product_type.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product_type.delete";
            $permission->alias = "Delete Product Types";
            $permission->description = "To delete a product type";
            $permission->permission_group = "Config";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product.index";
            $permission->alias = "View Products";
            $permission->description = "To view the list of products";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product.create";
            $permission->alias = "Create Products";
            $permission->description = "To create a new product";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product.edit";
            $permission->alias = "Edit Products";
            $permission->description = "To edit a product";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'product.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "product.delete";
            $permission->alias = "Delete Products";
            $permission->description = "To delete a product";
            $permission->permission_group = "Query";

            $permission->save();
        }

        // $permission = Permission::where('name', '=', 'trim.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "trim.index";
        //     $permission->alias = "View Trims";
        //     $permission->description = "To view the list of trims";
        //     $permission->permission_group = "Query";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'trim.create')->first();
        
        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "trim.create";
        //     $permission->alias = "Create Trims";
        //     $permission->description = "To create a new trim";
        //     $permission->permission_group = "Query";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'trim.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "trim.edit";
        //     $permission->alias = "Edit Trims";
        //     $permission->description = "To edit a trim";
        //     $permission->permission_group = "Query";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'trim.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "trim.delete";
        //     $permission->alias = "Delete Trims";
        //     $permission->description = "To delete a trim";
        //     $permission->permission_group = "Query";

        //     $permission->save();
        // }

        $permission = Permission::where('name', '=', 'query.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.index";
            $permission->alias = "View Queries";
            $permission->description = "To view the list of queries";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.chat')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.chat";
            $permission->alias = "Chat with Client";
            $permission->description = "To chat with a client";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', "=", 'query.change_status')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.change_status";
            $permission->alias = "Change Query Status";
            $permission->description = "To change the status of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.history')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.history";
            $permission->alias = "View Query History";
            $permission->description = "To view the history of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.create";
            $permission->alias = "Create Queries";
            $permission->description = "To create a new query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.edit";
            $permission->alias = "Edit Queries";
            $permission->description = "To edit a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.delete";
            $permission->alias = "Delete Queries";
            $permission->description = "To delete a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.show')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.show";
            $permission->alias = "View Query Details";
            $permission->description = "To view the details of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.view_specification_sheet')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.view_specification_sheet";
            $permission->alias = "View Specification Sheet";
            $permission->description = "To view the specification sheet of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.store_specification_sheet')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.store_specification_sheet";
            $permission->alias = "Store Specification Sheet";
            $permission->description = "To store the specification sheet of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.view_specification_sheet')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.view_specification_sheet";
            $permission->alias = "View Specification Sheet";
            $permission->description = "To view the specification sheet of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.update_specification_sheet')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.update_specification_sheet";
            $permission->alias = "Update Specification Sheet";
            $permission->description = "To update the specification sheet of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.destroy_specification_sheet')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.destroy_specification_sheet";
            $permission->alias = "Delete Specification Sheet";
            $permission->description = "To delete the specification sheet of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }
        
        $permission = Permission::where('name', '=', 'query.print_specification_sheet')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.print_specification_sheet";
            $permission->alias = "Print Specification Sheet";
            $permission->description = "To print the specification sheet of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.assign_merchandiser')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.assign_merchandiser";
            $permission->alias = "Assign Merchandiser";
            $permission->description = "To assign a merchandiser to a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.view_merchandiser_assign_history')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.view_merchandiser_assign_history";
            $permission->alias = "View Assign History";
            $permission->description = "To view the assignment history of a query";
            $permission->permission_group = "Query";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'query.approve')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "query.approve";
            $permission->alias = "Approve PO Sheet";
            $permission->description = "To approve a query and PO sheet";
            $permission->permission_group = "Query";

            $permission->save();
        }

        // PERMISSION GROUP - ORDER



        $permission = Permission::where('name', '=', 'order.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.index";
            $permission->alias = "View Orders";
            $permission->description = "To view the list of orders";
            $permission->permission_group = "Order";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'order.create')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.create";
            $permission->alias = "Create Orders";
            $permission->description = "To create a new order";
            $permission->permission_group = "Order";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'order.edit')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.edit";
            $permission->alias = "Edit Orders";
            $permission->description = "To edit an order";
            $permission->permission_group = "Order";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'order.delete')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.delete";
            $permission->alias = "Delete Orders";
            $permission->description = "To delete an order";
            $permission->permission_group = "Order";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'order.add_tna')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.add_tna";
            $permission->alias = "Add TNA";
            $permission->description = "To add a TNA to an order";
            $permission->permission_group = "Order";

            $permission->save();
        }
        
        $permission = Permission::where('name', '=', 'order.edit_tna')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.edit_tna";
            $permission->alias = "Edit TNA";
            $permission->description = "To edit a TNA of an order";
            $permission->permission_group = "Order";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'order.print_tna')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.print_tna";
            $permission->alias = "Print TNA";
            $permission->description = "To print the TNA of an order";
            $permission->permission_group = "Order";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'order.delete_tna')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.delete_tna";
            $permission->alias = "Delete TNA";
            $permission->description = "To delete the TNA of an order";
            $permission->permission_group = "Order";

            $permission->save();
        }

        $permission = Permission::where('name', '=', 'order.view_tna')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "order.view_tna";
            $permission->alias = "View TNA";
            $permission->description = "To view the TNA of an order";
            $permission->permission_group = "Order";

            $permission->save();
        }

        // PERMISSION GROUP - EXPENSE

        // $permission = Permission::where('name', '=', 'expense_categories.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense_categories.index";
        //     $permission->alias = "View Expense Categories";
        //     $permission->description = "To view the list of expense categories";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense_categories.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense_categories.create";
        //     $permission->alias = "Create Expense Categories";
        //     $permission->description = "To create a new expense category";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense_categories.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense_categories.edit";
        //     $permission->alias = "Edit Expense Categories";
        //     $permission->description = "To edit an expense category";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense_categories.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense_categories.delete";
        //     $permission->alias = "Delete Expense Categories";
        //     $permission->description = "To delete a new expense category";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.index";
        //     $permission->alias = "View Expenses";
        //     $permission->description = "To view the list of expenses";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.create";
        //     $permission->alias = "Create Expenses";
        //     $permission->description = "To create a new expense";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.edit";
        //     $permission->alias = "Edit Expenses";
        //     $permission->description = "To edit an expense";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.payment.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.payment.index";
        //     $permission->alias = "View Expense Payments";
        //     $permission->description = "To view the list of expense payments";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.payment.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.payment.create";
        //     $permission->alias = "Create Expense Payments";
        //     $permission->description = "To create a new expense payment";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.payment.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.payment.edit";
        //     $permission->alias = "Edit Expense Payments";
        //     $permission->description = "To edit an expense payment";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.print')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.print";
        //     $permission->alias = "Print Expenses";
        //     $permission->description = "To print an expense";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'expense.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "expense.delete";
        //     $permission->alias = "Delete Expenses";
        //     $permission->description = "To delete an expense";
        //     $permission->permission_group = "Expense";

        //     $permission->save();
        // }
        

        // // PERMISSION GROUP - REVENUE

        // $permission = Permission::where('name', '=', 'revenue_categories.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue_categories.index";
        //     $permission->alias = "View Revenue Categories";
        //     $permission->description = "To view the list of revenue categories";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue_categories.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue_categories.create";
        //     $permission->alias = "Create Revenue Categories";
        //     $permission->description = "To create a new revenue category";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue_categories.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue_categories.edit";
        //     $permission->alias = "Edit Revenue Categories";
        //     $permission->description = "To edit a revenue category";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue.index";
        //     $permission->alias = "View Revenues";
        //     $permission->description = "To view the list of revenues";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue.create";
        //     $permission->alias = "Create Revenues";
        //     $permission->description = "To create a new revenue";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue.edit";
        //     $permission->alias = "Edit Revenues";
        //     $permission->description = "To edit a revenue";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue.payment.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue.payment.index";
        //     $permission->alias = "View Revenue Payments";
        //     $permission->description = "To view the list of revenue payments";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue.payment.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue.payment.create";
        //     $permission->alias = "Create Revenue Payments";
        //     $permission->description = "To create a new revenue payment";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue.payment.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue.payment.edit";
        //     $permission->alias = "Edit Revenue Payments";
        //     $permission->description = "To edit a revenue payment";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'revenue.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "revenue.delete";
        //     $permission->alias = "Delete Revenues";
        //     $permission->description = "To delete a revenue";
        //     $permission->permission_group = "Revenue";

        //     $permission->save();
        // }


        // PERMISSION GROUP - PAYMENT

        // $permission = Permission::where('name', '=', 'payment.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "payment.index";
        //     $permission->alias = "View Payments";
        //     $permission->description = "To view the list of payments";
        //     $permission->permission_group = "Payment";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'payment.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "payment.create";
        //     $permission->alias = "Create Payments";
        //     $permission->description = "To create a new payment";
        //     $permission->permission_group = "Payment";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'payment.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "payment.edit";
        //     $permission->alias = "Edit Payments";
        //     $permission->description = "To edit a payment";
        //     $permission->permission_group = "Payment";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'payment.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "payment.delete";
        //     $permission->alias = "Delete Payments";
        //     $permission->description = "To delete a payment";
        //     $permission->permission_group = "Payment";

        //     $permission->save();
        // }

        // PERMISSION GROUP - ACCOUNT

        // $permission = Permission::where('name', '=', 'account_category.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "account_category.index";
        //     $permission->alias = "View Account Categories";
        //     $permission->description = "To view the list of account categories";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'account_category.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "account_category.create";
        //     $permission->alias = "Create Account Categories";
        //     $permission->description = "To create a new account category";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'account_category.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "account_category.edit";
        //     $permission->alias = "Edit Account Categories";
        //     $permission->description = "To edit an account category";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'account.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "account.index";
        //     $permission->alias = "View Accounts";
        //     $permission->description = "To view the list of accounts";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'account.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "account.create";
        //     $permission->alias = "Create Accounts";
        //     $permission->description = "To create a new account";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'account.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "account.edit";
        //     $permission->alias = "Edit Accounts";
        //     $permission->description = "To edit an account";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'account_statement.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "account_statement.index";
        //     $permission->alias = "View Account Statements";
        //     $permission->description = "To view the list of account statements";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'add_withdraw_money.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "add_withdraw_money.index";
        //     $permission->alias = "View Add/Withdraw Money";
        //     $permission->description = "To view the list of add/withdraw money";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'add_withdraw_money.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "add_withdraw_money.create";
        //     $permission->alias = "Create Add/Withdraw Money";
        //     $permission->description = "To create a new add/withdraw money";
        //     $permission->permission_group = "Account";

        //     $permission->save();
        // }

        // // PERMISSION GROUP - FINANCIAL

        // $permission = Permission::where('name', '=', 'money_transfer.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "money_transfer.index";
        //     $permission->alias = "View Money Transfers";
        //     $permission->description = "To view the list of money transfers";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'money_transfer.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "money_transfer.create";
        //     $permission->alias = "Create Money Transfers";
        //     $permission->description = "To create a new money transfer";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'money_transfer.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "money_transfer.edit";
        //     $permission->alias = "Edit Money Transfers";
        //     $permission->description = "To edit a money transfer";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'money_transfer.delete')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "money_transfer.delete";
        //     $permission->alias = "Delete Money Transfers";
        //     $permission->description = "To delete a money transfer";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'loan.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "loan.index";
        //     $permission->alias = "View Loans";
        //     $permission->description = "To view the list of loans";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'loan.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "loan.create";
        //     $permission->alias = "Create Loans";
        //     $permission->description = "To create a new loan";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'investment.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "investment.index";
        //     $permission->alias = "View Investments";
        //     $permission->description = "To view the list of investments";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'investment.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "investment.create";
        //     $permission->alias = "Create Investments";
        //     $permission->description = "To create a new investment";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'investor.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "investor.index";
        //     $permission->alias = "View Investors";
        //     $permission->description = "To view the list of investors";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'investor.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "investor.create";
        //     $permission->alias = "Create Investors";
        //     $permission->description = "To create a new investor";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'investor.edit')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "investor.edit";
        //     $permission->alias = "Edit Investors";
        //     $permission->description = "To edit an investor";
        //     $permission->permission_group = "Financial";

        //     $permission->save();
        // }
        
        // // PERMISSION GROUP - TRANSACTION POOL

        // $permission = Permission::where('name', '=', 'transaction_pool.ignore')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "transaction_pool.ignore";
        //     $permission->alias = "Ignore Transaction Pool";
        //     $permission->description = "To ignore the Transaction Pool when making payments";
        //     $permission->permission_group = "Transaction Pool";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'transaction_pool.index')->first();
        
        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "transaction_pool.index";
        //     $permission->alias = "View Transaction Pool";
        //     $permission->description = "To view the list of transactions in the pool";
        //     $permission->permission_group = "Transaction Pool";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'transaction_pool.check')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "transaction_pool.check";
        //     $permission->alias = "Check Transaction Pool";
        //     $permission->description = "To check the transactions in the pool";
        //     $permission->permission_group = "Transaction Pool";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'transaction_pool.approve')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "transaction_pool.approve";
        //     $permission->alias = "Approve Transaction Pool";
        //     $permission->description = "To approve the transactions in the pool";
        //     $permission->permission_group = "Transaction Pool";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'transaction_pool.reject')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "transaction_pool.reject";
        //     $permission->alias = "Reject Transaction Pool";
        //     $permission->description = "To reject the transactions in the pool";
        //     $permission->permission_group = "Transaction Pool";

        //     $permission->save();
        // }

        // // PERMISSION GROUP - REPORT

        // $permission = Permission::where('name', '=', 'report.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "report.index";
        //     $permission->alias = "View Reports";
        //     $permission->description = "To view the list of reports";
        //     $permission->permission_group = "Report";

        //     $permission->save();
        // }

        // PERMISSION GROUP - CONFIG
        // $permission = Permission::where('name', '=', 'config.index')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "config.index";
        //     $permission->alias = "View Configurations";
        //     $permission->description = "To view the list of configurations";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'config.create')->first();

        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "config.create";
        //     $permission->alias = "Create Configurations";
        //     $permission->description = "To create a new configuration";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // $permission = Permission::where('name', '=', 'config.edit')->first();
        
        // if($permission == null)
        // {
        //     $permission = new Permission();

        //     $permission->name = "config.edit";
        //     $permission->alias = "Edit Configurations";
        //     $permission->description = "To edit a configuration";
        //     $permission->permission_group = "Config";

        //     $permission->save();
        // }

        // PERMISSION GROUP - SETTINGS

        $permission = Permission::where('name', '=', 'setting.index')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "setting.index";
            $permission->alias = "Manage Settings";
            $permission->description = "To manage the settings";
            $permission->permission_group = "Settings";

            $permission->save();
        }

                //see everything
        $permission = Permission::where('name', '=', 'see_everything')->first();

        if($permission == null)
        {
            $permission = new Permission();

            $permission->name = "see_everything";
            $permission->alias = "See Everything";
            $permission->description = "To see everything";
            $permission->permission_group = "Super Admin";

            $permission->save();
        }

    }
}
