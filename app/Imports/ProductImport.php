<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Price;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // import to database
        DB::beginTransaction();

        try {

            foreach ($collection as $key => $row) {
                if($key == 0){
                    $product_category = new ProductCategory();
                    $product_category->name = $row[0];
                    $product_category->code =  $this->generateUniqueCode();
                    $product_category->status = 'Active';
                    $product_category->save();
                }
                if ($key >= 2) {
                    if($row[0] == null || $row[1] == null || $row[4] == null){
                        continue;
                    }
                    $unit = Unit::where('abbreviation', $row[4])->first();
                    
                    if($unit == null){
                        $unit = new Unit();
                        $unit->name = $row[4];
                        $unit->abbreviation = $row[4];
                        $unit->status = 'Active';
                        $unit->save();
                    }

                    $product = new Product();
                    $product->name = $row[0];
                    $product->product_category_id = ProductCategory::latest()->first()->id;
                    $sku = $this->generateProductUniqueCode();
                    $product_sku = Product::where('sku', 'like', '%' . $categoryValue . '%')->latest()->first();
                    if($product_sku != null){
                        $sku = $this->generateProductUniqueCode();
                    }
                    $product->sku = $sku;
                    $product->product_unit_id = $unit->id;
                    $product->purchase_unit_id = $unit->id;
                    $product->sale_unit_id = $unit->id;
                    $product->alert_quantity = 10;
                    $product->save();

                    $price = new Price();
                    $price->date = Carbon::now()->toDateTimeString();
                    $price->product_id = $product->id;
                    $price->purchase_price = $row[3] ?? 0;
                    $price->retail_price = $row[2] ?? 0;
                    $price->save();

                    $inventory = new Inventory();
                    $inventory->product_id = $product->id;
                    $inventory->warehouse_id = session('user_warehouse')->id;
                    $inventory->available_quantity = $row[1];
                    $inventory->save();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
    }

    public function generateRandomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function generateUniqueCode()
    {
        $timestamp = time();
        $randomString = $this->generateRandomString(2);
        $uniqueCode = $timestamp . $randomString;
        return $uniqueCode;
    }

    public function generateProductUniqueCode()
    {
        $timestamp = time();
        $categoryValue = ProductCategory::latest()->first()->name;
        $length = 12;
        $characters = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        $uniqueCode = $categoryValue . '-' . $code;
        return $uniqueCode;
    }

}
