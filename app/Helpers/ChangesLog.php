<?php

namespace App;

use App\Models\ChangeLog;

class ChangesLogHelper 
{
    public static function add($data) 
    {
        ChangeLog::create($data);
    }
}
