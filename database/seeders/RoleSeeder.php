<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', '=', 'super_admin')->first();

        if($role == null)
        {
            $role = new Role();

            $role->name = "super_admin";

            $role->description = "This role has the highest authority with all the permissions and no restrictions.";

            $role->save();
        }

        $role = Role::where('name', '=', 'buyer')->first();

        if($role == null)
        {
            $role = new Role();

            $role->name = "buyer";

            $role->description = "This role has the authority to make queries and view queries.";

            $role->editable = false;

            $role->save();
        }

        $role = Role::where('name', '=', 'merchandiser')->first();

        if($role == null)
        {
            $role = new Role();

            $role->name = "merchandiser";

            $role->description = "This role is assigned to queries and orders.";

            $role->editable = false;

            $role->save();
        }


        
    }
}
