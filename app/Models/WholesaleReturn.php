<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Wholesale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WholesaleReturn extends Model
{
    use HasFactory;
    
    public function wholesale()
    {
        return $this->belongsTo(Wholesale::class);
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

    public function wholesale_return_products()
    {
        return $this->hasMany(WholesaleReturnProduct::class);
    }
}
