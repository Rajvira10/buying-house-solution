<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', '=', 'admin@gmail.com')->first();

        if($user == null)
        {
            $user = new User();

            $user->username = "admin";

            $user->email = "admin@gmail.com";

            $user->password = Hash::make("11111111");

            $user->save();


            $role = Role::where('name', '=', 'super_admin')->first();

            $user->roles()->attach($role->id);
        }


    }
}
