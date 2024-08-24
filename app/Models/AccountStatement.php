<?php

namespace App\Models;

use App\Models\Accounts\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountStatement extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
