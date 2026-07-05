<?php

namespace App\Models;

use App\Core\Model;

class Employee extends Model
{
    protected static string $table = 'tblEmployees';
    protected static string $primaryKey = 'employee_id';
}