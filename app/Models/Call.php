<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use App\Models\CallType;
use App\Models\CallStatus;
use App\Models\ContactPerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Call extends Model
{
    use HasFactory;

    protected $casts = [
        'call_date' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contact_person()
    {
        return $this->belongsTo(ContactPerson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function call_type()
    {
        return $this->belongsTo(CallType::class);
    }

    public function call_status()
    {
        return $this->belongsTo(CallStatus::class);
    }
}
