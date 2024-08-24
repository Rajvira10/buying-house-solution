<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\User;
use App\Models\Payment;
use App\Models\Warehouse;
use App\Models\SaleReturnProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleReturn extends Model
{
    use HasFactory;

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function sale_return_products()
    {
        return $this->hasMany(SaleReturnProduct::class);
    }
}
