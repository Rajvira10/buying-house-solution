<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department = Department::where('name', '=', 'Merchandiser')->first();

        if($department == null)
        {
            $department = new Department();

            $department->name = "Merchandiser";

            $department->editable = false;

            $department->save();
        }
    }
}
