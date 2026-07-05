<?php

namespace App\Services;
use App\Models\Student;

class StudentService
{
    public static function count(): int
    {
        return Student::count();
    }

    public static function activeCount(): int
    {
        return Student::where('status', '=', 'Active')
            ->count();
    }

    public static function recent(int $limit = 10): array
    {
        return Student::orderBy('student_id', 'DESC')
            ->limit($limit)
            ->get();
    }
}