<?php

namespace App\Models;

use App\Models\File;
use App\Models\Price;
use App\Models\Inventory;
use App\Models\SaleProduct;
use App\Models\PurchaseProduct;
use App\Models\WholesaleProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function price()
    {
        return $this->hasMany(Price::class);
    }

    public function purchase_products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }

    public function sale_products()
    {
        return $this->hasMany(SaleProduct::class);
    }

    public function wholesale_products()
    {
        return $this->hasMany(WholesaleProduct::class);
    }

    public function image()
    {
        return $this->hasOne(File::class, 'id', 'image_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
}
