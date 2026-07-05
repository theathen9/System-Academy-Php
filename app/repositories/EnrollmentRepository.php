<?php

namespace App\Repositories;

use App\Models\Enrollment;

class EnrollmentRepository
{
    public function all()
    {
        return Enrollment::all();
    }

    public function enrollStudent(int $studentId, int $courseId)
    {
        return Enrollment::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'enroll_date' => date('Y-m-d')
        ]);
    }

    public function getByStudent(int $studentId)
    {
        return Enrollment::query()
            ->where('student_id', '=', $studentId)
            ->get();
    }

    public function count()
    {
        return Enrollment::query()->count();
    }
}