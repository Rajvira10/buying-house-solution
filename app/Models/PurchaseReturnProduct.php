<?php

namespace App\Models;

use App\Models\Price;
use App\Models\Product;
use App\Models\PurchaseReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseReturnProduct extends Model
{
    use HasFactory;

    protected $table = 'purchase_return_product';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase_return()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }
}
