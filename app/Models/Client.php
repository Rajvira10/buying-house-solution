<?php

namespace App\Models;

use App\Models\Call;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Meeting;
use App\Models\ClientSource;
use App\Models\ClientStatus;
use App\Models\InterestedIn;
use App\Models\ContactPerson;
use App\Models\BusinessCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'client_source_id',
        'business_category_id',
        'interested_in_id',
        'client_status_id',
        'note',
        'created_by',
        'updated_by',
    ];
    
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function client_source()
    {
        return $this->belongsTo(ClientSource::class);
    }

    public function business_category()
    {
        return $this->belongsTo(BusinessCategory::class);
    }

    public function interested_in()
    {
        return $this->belongsTo(InterestedIn::class);
    }

    public function client_status()
    {
        return $this->belongsTo(ClientStatus::class);
    }

    public function contact_persons()
    {
        return $this->hasMany(ContactPerson::class);
    }

    public function calls()
    {
        return $this->hasMany(Call::class);
    }
    
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}   
