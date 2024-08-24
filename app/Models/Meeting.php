<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use App\Models\MeetingType;
use App\Models\MeetingTitle;
use App\Models\MeetingMinute;
use App\Models\MeetingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    public function meeting_type()
    {
        return $this->belongsTo(MeetingType::class);
    }

    public function meeting_status()
    {
        return $this->belongsTo(MeetingStatus::class);
    }

    public function meeting_title()
    {
        return $this->belongsTo(MeetingTitle::class);
    }

    public function meeting_minutes()
    {
        return $this->hasMany(MeetingMinute::class);
    }
}
