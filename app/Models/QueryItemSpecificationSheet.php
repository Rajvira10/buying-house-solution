<?php

namespace App\Models;

use App\Models\QueryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueryItemSpecificationSheet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
        'approximate_delivery_date' => 'datetime',
        'AWB_date' => 'datetime',
    ];

    public function queryItem()
    {
        return $this->belongsTo(QueryItem::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }
}
