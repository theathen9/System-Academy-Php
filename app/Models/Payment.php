<?php

namespace App\Models;

use App\Core\Model;

class Payment extends Model
{
    protected static string $table = 'tblPayments';
    protected static string $primaryKey = 'payment_id';
}