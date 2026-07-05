<?php

namespace App\Models;

use App\Core\Model;

class ExamType extends Model
{
    protected static string $table = 'tblExamTypes';
    protected static string $primaryKey = 'exam_type_id';
}