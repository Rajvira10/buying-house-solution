<?php

namespace App\Models;

use App\Models\User;
use App\Models\BuyerContactPerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contact_people()
    {
        return $this->hasMany(BuyerContactPerson::class);
    }
}
