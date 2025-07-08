<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'relationship', 'mobile', 'email', 'user_id',
    ];
}
