<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\SalaryStructure;

class PayrollService
{
    public function calculateGrossSalary($detail)
    {
        $salary_structure = SalaryStructure::where('employee_id', '=', $detail->employee_id)
        ->orderBy('start_date', 'desc')
        ->first();

        $h_rent = $salary_structure->gross * $salary_structure->h_rent_percent / 100;

        $med = $salary_structure->gross * $salary_structure->med_percent / 100;

        $conveyance = $salary_structure->gross * $salary_structure->conv_percent / 100;

        $basic_salary = $salary_structure->gross - $h_rent - $med - $conveyance;

        return [$salary_structure->gross, $basic_salary, $detail->bonus, $h_rent, $med, $conveyance ];
    }

    public function calculateNetSalary($detail)
    {
        [$gross_salary, $basic, $bonus] = $this->calculateGrossSalary($detail);
        
        $payroll_month = Payroll::find($detail->payroll_id)->month;

        $days_in_month = Carbon::parse($payroll_month)->daysInMonth;

        $deduction = $detail->days_absent * $basic / $days_in_month;

        $net_salary = $gross_salary - $deduction + $bonus;

        return [$deduction, $net_salary];
    }
    
}