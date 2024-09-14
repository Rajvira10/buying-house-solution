<?php

namespace App\Models;

use App\Models\File;
use App\Models\Trim;
use App\Models\Query;
use App\Models\Product;
use App\Models\QueryTrim;
use App\Models\QueryImage;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use App\Models\QueryItemSpecificationSheet;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueryItem extends Model
{
    use HasFactory;

    public function queryModel()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }

    public function images()
    {
        return $this->belongsToMany(File::class, 'query_images');
    }

    public function trims()
    {
        return $this->belongsToMany(Trim::class, 'query_trims');
    }

    public function measurements()
    {
        return $this->belongsToMany(File::class, 'query_measurements');
    }

    public function getTrimIds()
    {
        return $this->trims->pluck('id')->toArray();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function specificationSheets()
    {
        return $this->hasMany(QueryItemSpecificationSheet::class)->orderBy('date', 'desc');
    }
}
