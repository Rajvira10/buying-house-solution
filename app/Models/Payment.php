<?php

namespace App\Models;

use App\Models\User;
use App\Models\Account;
use App\Models\TransactionPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function transactionPool()
    {
        return $this->morphOne(TransactionPool::class, 'poolable');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
