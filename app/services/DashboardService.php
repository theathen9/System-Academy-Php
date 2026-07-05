<?php

namespace App\Services;

class DashboardService
{
    public static function cards(): array
    {
        return [

            'students' =>
                StudentService::count(),

            'enrollments' =>
                EnrollmentService::count(),

            'revenue' =>
                PaymentService::totalRevenue(),

            'attendance' =>
                AttendanceService::rate(),

            'employees' =>
                EmployeeService::count()
        ];
    }
}