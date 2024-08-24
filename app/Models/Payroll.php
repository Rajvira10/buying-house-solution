<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Warehouse;
use App\Models\PayrollDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function payroll_details()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
