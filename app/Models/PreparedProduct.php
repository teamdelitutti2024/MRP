<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparedProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'product_key',
    ];

    public function ingredients()
    {
        return $this->hasMany(PreparedProductIngredient::class);
    }

    public function resources()
    {
        return $this->hasMany(PreparedProductResource::class);
    }
}
