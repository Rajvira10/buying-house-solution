<?php

namespace App\Models;

use App\Models\FactoryContactPerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factory extends Model
{
    use HasFactory;

    public function contact_people()
    {
        return $this->hasMany(FactoryContactPerson::class);
    }
}
