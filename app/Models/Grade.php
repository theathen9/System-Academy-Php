<?php

namespace App\Models;

use App\Core\Model;

class Grade extends Model
{
    protected static string $table = 'tblGrade';
    protected static string $primaryKey = 'grade_id';
}