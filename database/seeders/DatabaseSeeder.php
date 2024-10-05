<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\TNASeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AccountSeeder;
use Database\Seeders\WarehouseSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\StatesTableSeeder;
use Database\Seeders\CountriesTableSeeder;
use Database\Seeders\CitiesTableChunkOneSeeder;
use Database\Seeders\CitiesTableChunkTwoSeeder;
use Database\Seeders\CitiesTableChunkFiveSeeder;
use Database\Seeders\CitiesTableChunkFourSeeder;
use Database\Seeders\CitiesTableChunkThreeSeeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AccountSeeder::class,
            WarehouseSeeder::class,
            DepartmentSeeder::class,
            TNASeeder::class,
            ProductTypeSeeder::class,
        ]);

        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableChunkOneSeeder::class);
        $this->call(CitiesTableChunkTwoSeeder::class);
        $this->call(CitiesTableChunkThreeSeeder::class);
        $this->call(CitiesTableChunkFourSeeder::class);
        $this->call(CitiesTableChunkFiveSeeder::class);
    }
}
