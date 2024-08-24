<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function logo()
    {
        return $this->belongsTo(File::class, 'logo_id');
    }

    public function favicon()
    {
        return $this->belongsTo(File::class, 'favicon_id');
    }
}
