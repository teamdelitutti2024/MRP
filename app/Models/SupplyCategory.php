<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
