<?php

namespace App\Services;
use App\Models\Enrollment;

class EnrollmentService
{
    public static function count(): int
    {
        return Enrollment::count();
    }

    public static function trend(): array
    {
        return Enrollment::select([
                'DATE(created_at) as date',
                'COUNT(*) as total'
            ])
            ->groupBy('DATE(created_at)')
            ->get();
    }
}