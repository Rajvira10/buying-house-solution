<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Inventory;
use App\Models\GudamProduct;
use Illuminate\Console\Command;

class UpdateGudamStock extends Command
{
    protected $signature = 'gudamstock:update';
    protected $description = 'Update GudamStock entries';

    public function handle()
    {
        $gudam_products = GudamProduct::all();

        $grouped_products = $gudam_products->groupBy(['warehouse_id', 'product_id']);

        foreach ($grouped_products[1] as $index=>$item) {

                $item = $item[0];
                
                $gudam_available_quantity = Inventory::where("warehouse_id", $item->warehouse_id)
                    ->where("product_id", $item->product_id)
                    ->value('gudam_available_quantity');

                $closing_stock = new GudamProduct();
                $closing_stock->warehouse_id = $item->warehouse_id;
                $closing_stock->product_id = $item->product_id;
                $closing_stock->type = "Closing Stock";
                $closing_stock->date = Carbon::yesterday()->endOfDay();
                $closing_stock->quantity = $gudam_available_quantity;
                $closing_stock->save();

                $opening_stock = new GudamProduct();
                $opening_stock->warehouse_id = $item->warehouse_id;
                $opening_stock->product_id = $item->product_id;
                $opening_stock->type = "Opening Stock";
                $opening_stock->date = Carbon::today();
                $opening_stock->quantity = $gudam_available_quantity; 
                $opening_stock->save();
        }

        $this->info('GudamStock entries updated successfully.');
    }
}
