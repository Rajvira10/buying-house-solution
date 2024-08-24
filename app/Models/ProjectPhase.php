<?php

namespace App\Models;

use App\Models\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectPhase extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function projectPhaseStatus()
    {
        return $this->belongsTo(ProjectStatus::class);
    }
}
