<?php

namespace App\Models;

use App\Models\Query;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueryMerchandiser extends Model
{
    use HasFactory;

    protected $fillable = ['query_id', 'employee_id'];

    public function queryModel()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
