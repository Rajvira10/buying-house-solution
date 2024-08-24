<?php

namespace App\Models;

use App\Models\User;
use App\Models\Account;
use App\Models\TransactionPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoneyTransfer extends Model
{
    use HasFactory;

    protected $table = 'money_transfers';

    public function senderAccount()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }

    public function receiverAccount()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }
    
    public function transactionPool()
    {
        return $this->morphOne(TransactionPool::class, 'poolable');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by', 'id');
    }
}
