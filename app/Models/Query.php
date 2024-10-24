<?php

namespace App\Models;

use App\Models\File;
use App\Models\Trim;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Query;
use App\Models\Employee;
use App\Models\QueryChat;
use App\Models\QueryItem;
use App\Models\ProductType;
use App\Models\QueryMerchandiser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Query extends Model
{
    use HasFactory;

    protected $casts = [
        'query_date' => 'datetime',
    ];

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Query::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Query::class, 'parent_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    

    public function items()
    {
        return $this->hasMany(QueryItem::class);
    }

    public function merchandiser()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function product_type()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function merchandisers()
    {
        return $this->belongsToMany(QueryMerchandiser::class, 'query_merchandisers');
    }

    public function messages()
    {
        return $this->hasMany(QueryChat::class);
    }
    
}
