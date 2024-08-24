<?php

namespace App\Models;

use App\Models\User;
use App\Models\Investor;
use App\Models\TransactionPool;
use App\Models\InvestmentReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;

    public function transactionPool()
    {
        return $this->morphOne(TransactionPool::class, 'poolable');
    }

    public function returns()
    {
        return $this->hasMany(InvestmentReturn::class);
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by', 'id');
    }
}
