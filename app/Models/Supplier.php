<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Thana;
use App\Models\Account;
use App\Models\Payment;
use App\Models\District;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Models\PurchaseReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
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

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'unique_id', 'name');
    }
}
