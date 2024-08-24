<?php

namespace App\Models;

use App\Models\User;
use App\Models\Investment;
use App\Models\TransactionPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestmentReturn extends Model
{
    use HasFactory;

    public function transactionPool()
    {
        return $this->morphOne(TransactionPool::class, 'poolable');
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by', 'id');
    }

}
