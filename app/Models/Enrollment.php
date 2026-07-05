<?php

namespace App\Models;

use App\Core\Model;

class Enrollment extends Model
{
    protected static string $table = 'tblEnrollments';
    protected static string $primaryKey = 'enrollment_id';
}