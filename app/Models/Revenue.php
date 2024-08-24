<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\RevenueCategory;
use App\Models\TransactionPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Revenue extends Model
{
    use HasFactory;

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by', 'id');
    }

    public function revenueCategory()
    {
        return $this->belongsTo(RevenueCategory::class);
    }


    public function transactionPool()
    {
        return $this->morphOne(TransactionPool::class, 'poolable');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
