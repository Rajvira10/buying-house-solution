<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_category = new AccountCategory();
        
        $account_category->name = 'Supplier Account';

        $account_category->save();

        $account_category = new AccountCategory();

        $account_category->name = 'Client Account';

        $account_category->save();
    }
}
