<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Sale;
use App\Models\Thana;
use App\Models\Account;
use App\Models\Payment;
use App\Models\District;
use App\Models\Warehouse;
use App\Models\Wholesale;
use App\Models\SaleReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    public function payments()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function thana()
    {
        return $this->belongsTo(Thana::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function wholesales()
    {
        return $this->hasMany(Wholesale::class);
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class);
    }
    
    public function account()
    {
        return $this->belongsTo(Account::class, 'unique_id', 'name');
    }
}
