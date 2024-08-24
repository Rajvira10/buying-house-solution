<?php

namespace App\Models;

use App\Models\User;
use App\Models\LoanClient;
use App\Models\LoanPayback;
use App\Models\TransactionPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    public function loanClient()
    {
        return $this->belongsTo(LoanClient::class);
    }

    public function transactionPool()
    {
        return $this->morphOne(TransactionPool::class, 'poolable');
    }

    public function paybacks()
    {
        return $this->hasMany(LoanPayback::class);
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by', 'id');
    }
}
