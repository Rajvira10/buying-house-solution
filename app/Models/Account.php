<?php

namespace App\Models;

use App\Models\AccountCategory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountStatement;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    public function account_statements()
    {
        return $this->hasMany(AccountStatement::class);
    }

    public function account_category()
    {
        return $this->belongsTo(AccountCategory::class);
    }
}
