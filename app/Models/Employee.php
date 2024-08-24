<?php

namespace App\Models;

use App\Models\Payroll;
use App\Models\Warehouse;
use App\Models\Department;
use App\Models\JobDuration;
use App\Models\SalaryStructure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    public function job_durations()
    {
        return $this->hasMany(JobDuration::class);
    }

    public function salary_structures()
    {
        return $this->hasMany(SalaryStructure::class);
    }

    public function payroll()
    {
        return $this->belongsToMany(Payroll::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
