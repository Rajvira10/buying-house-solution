<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\User;
use App\Models\TransactionPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanPayback extends Model
{
    use HasFactory;

    public function transactionPool()
    {
        return $this->morphOne(TransactionPool::class, 'poolable');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by', 'id');
    }
}

