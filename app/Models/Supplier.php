<?php

namespace App\Models;

use App\Models\SupplierContactPerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    public function contact_people()
    {
        return $this->hasMany(SupplierContactPerson::class);
    }
}
