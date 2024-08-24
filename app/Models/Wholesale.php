<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\WholesaleReturn;
use App\Models\WholesaleProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wholesale extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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

    public function wholesale_products()
    {
        return $this->hasMany(WholesaleProduct::class);
    }

    public function wholesale_returns()
    {
        return $this->hasMany(WholesaleReturn::class);
    }
}
