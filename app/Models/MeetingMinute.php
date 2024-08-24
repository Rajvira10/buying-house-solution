<?php

namespace App\Models;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingMinute extends Model
{
    use HasFactory;

    protected $fillable = ['meeting_id', 'note'];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
