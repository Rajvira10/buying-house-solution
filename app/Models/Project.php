<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use App\Models\Payment;
use App\Models\ProjectType;
use App\Models\ProjectPhase;
use App\Models\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    public function project_type()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function project_status()
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    public function project_phases()
    {
        return $this->hasMany(ProjectPhase::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
}
