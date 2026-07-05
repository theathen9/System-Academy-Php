<?php

namespace App\Models;

use App\Core\Model;

class Score extends Model
{
    protected static string $table = 'tblScores';
    protected static string $primaryKey = 'score_id';
}