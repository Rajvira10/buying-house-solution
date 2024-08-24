<?php

namespace App\Models;

use App\Models\Price;
use App\Models\Product;
use App\Models\SaleReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleReturnProduct extends Model
{
    use HasFactory;

    protected $table = 'sale_return_product';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale_return()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }
}
