<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakeBreadProductionDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bake_breads_production_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bake_bread_size_name', 'quantity', 'bake_bread_size_id', 'bake_breads_production_id'
    ];

    public function bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class);
    }
}
