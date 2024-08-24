<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $warehouse = new Warehouse();

        $warehouse->name = 'Warehouse 1';

        $warehouse->contact_no = '1234567890';

        $warehouse->email = 'hello@gmail.com';

        $warehouse->status = 'Active';

        $warehouse->address = 'Dhaka, Bangladesh';

        $warehouse->description = 'This is a test warehouse';

        $warehouse->save();
    }
}
