<?php

namespace App\Models;

use App\Models\File;
use App\Models\Trim;
use App\Models\Buyer;
use App\Models\Query;
use App\Models\QueryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Query extends Model
{
    use HasFactory;

    protected $casts = [
        'query_date' => 'datetime',
    ];

    public function children()
    {
        return $this->hasMany(Query::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Query::class, 'parent_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function items()
    {
        return $this->hasMany(QueryItem::class);
    }
}
