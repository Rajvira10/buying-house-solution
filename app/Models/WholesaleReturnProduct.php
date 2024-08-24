<?php

namespace App\Models;

use App\Models\Price;
use App\Models\Product;
use App\Models\WholesaleReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WholesaleReturnProduct extends Model
{
    use HasFactory;

    protected $table = 'wholesale_return_product';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function wholesale_return()
    {
        return $this->belongsTo(WholesaleReturn::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }
}
