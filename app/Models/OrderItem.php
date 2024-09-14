<?php

namespace App\Models;

use App\Models\Order;
use App\Models\OrderItemColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'shipment_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function colors()
    {
        return $this->hasMany(OrderItemColor::class);
    }
}
