<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakeBreadSize extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'bake_bread_size_key', 'complexity',
    ];

    public function ingredients()
    {
        return $this->hasMany(BakeBreadIngredient::class);
    }

    public function prepared_product_data()
    {
        return $this->hasMany(BakeBreadSizePreparedData::class);
    }

    public function resources()
    {
        return $this->hasMany(BakeBreadSizeResource::class);
    }

    public function bake_bread_sizes()
    {
        return $this->hasMany(BakeBreadSizeBakeBread::class);
    }
}
