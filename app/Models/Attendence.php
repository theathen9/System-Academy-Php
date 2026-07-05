<?php

namespace App\Models;

use App\Core\Model;

class Attendance extends Model
{
    protected static string $table = 'tblAttendances';
    protected static string $primaryKey = 'attendance_id';
}