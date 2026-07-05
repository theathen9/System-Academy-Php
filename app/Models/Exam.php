<?php

namespace App\Models;

use App\Core\Model;

class Exam extends Model
{
    protected static string $table = 'tblExams';
    protected static string $primaryKey = 'exam_id';
}