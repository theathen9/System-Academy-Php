<?php

namespace App\Models;

use App\Core\Model;

class Student extends Model
{
    protected static string $table = 'tblStudents';
    protected static string $primaryKey = 'student_id';
}