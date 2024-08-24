<?php

namespace App\Models;

use App\Models\Revenue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RevenueCategory extends Model
{
    use HasFactory;

    protected $table = 'revenue_categories';

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
}
