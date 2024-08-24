<?php

namespace App\Models;

use App\Models\File;
use App\Models\Trim;
use App\Models\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Query extends Model
{
    use HasFactory;

    public function children()
    {
        return $this->hasMany(Query::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Query::class, 'parent_id');
    }

    public function images()
    {
        return $this->belongsToMany(File::class, 'query_images');
    }

    public function measurements()
    {
        return $this->belongsToMany(File::class, 'query_measurements');
    }

    public function trims()
    {
        return $this->belongsToMany(Trim::class, 'query_trims');
    }

    public function getTrimIds()
    {
        return $this->trims->pluck('id')->toArray();
    }
}
