<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTransfer extends Model
{
    use HasFactory;

    public function senderWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'sender_warehouse_id');
    }

    public function receiverWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'receiver_warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
