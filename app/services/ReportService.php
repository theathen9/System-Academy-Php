<?php

namespace App\Services;

class ReportService
{
    public static function dashboard(): array
    {
        return DashboardService::cards();
    }

    public static function charts(): array
    {
        return [

            'enrollment' =>
                EnrollmentService::trend(),

            'revenue' =>
                PaymentService::revenueTrend(),

            'attendance' =>
                AttendanceService::trend()
        ];
    }
}