<?php

namespace App\Models;

use App\Models\Price;
use App\Models\Product;
use App\Models\Wholesale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WholesaleProduct extends Model
{
    use HasFactory;

    protected $table = 'wholesale_product';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function wholesale()
    {
        return $this->belongsTo(Wholesale::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }
}
