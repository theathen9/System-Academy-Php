<?php

namespace App\Repositories;

use App\Models\Attendance;

class AttendanceRepository
{
    public function mark(array $data)
    {
        return Attendance::create($data);
    }

    public function getByDate(string $date)
    {
        return Attendance::query()
            ->where('date', '=', $date)
            ->get();
    }

    public function presentCount()
    {
        return Attendance::query()
            ->where('status', '=', 'present')
            ->count();
    }

    public function absentCount()
    {
        return Attendance::query()
            ->where('status', '=', 'absent')
            ->count();
    }
}