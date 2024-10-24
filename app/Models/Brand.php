<?php

namespace App\Models;

use App\Models\Buyer;
use App\Models\BrandBank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function banks()
    {
        return $this->hasMany(BrandBank::class);
    }
}
