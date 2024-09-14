<?php

namespace App\Models;

use App\Models\Tna;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTna extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'plan_date' => 'datetime',
        'actual_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tna()
    {
        return $this->belongsTo(Tna::class);
    }
}
