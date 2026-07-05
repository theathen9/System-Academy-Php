<?php

namespace App\Services;
use App\Models\Attendance;
class AttendanceService
{
    public static function rate(): float
    {
        $total = Attendance::count();

        if ($total === 0) {
            return 0;
        }

        $present = Attendance::where(
                'status',
                '=',
                'Present'
            )
            ->count();

        return round(
            ($present / $total) * 100,
            2
        );
    }

    public static function trend(): array
    {
        return Attendance::select([
                'attendance_date',
                'COUNT(*) as total'
            ])
            ->groupBy('attendance_date')
            ->get();
    }
}