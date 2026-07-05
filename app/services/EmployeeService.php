<?php

namespace App\Services;
use App\Models\Employee;

class EmployeeService
{
    public static function count(): int
    {
        return Employee::count();
    }

    public static function activeCount(): int
    {
        return Employee::where(
                'status',
                '=',
                'Active'
            )
            ->count();
    }
}