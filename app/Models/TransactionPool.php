<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionPool extends Model
{
    use HasFactory;

    public function poolable()
    {
        return $this->morphTo();
    }
}
