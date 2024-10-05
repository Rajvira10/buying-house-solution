<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_type = new ProductType();
        $product_type->name = 'Woven';
        $product_type->save();

        $product_type = new ProductType();
        $product_type->name = 'Knit';
        $product_type->save();

        $product_type = new ProductType();
        $product_type->name = 'Sweater';
        $product_type->save();

    }
}
