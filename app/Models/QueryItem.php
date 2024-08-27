<?php

namespace App\Models;

use App\Models\File;
use App\Models\Query;
use App\Models\QueryTrim;
use App\Models\QueryImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueryItem extends Model
{
    use HasFactory;



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
}
