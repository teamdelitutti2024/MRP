<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProductionBakeBreadLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products_production_bb_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'bake_bread_size_id', 'production_id',
    ];

    public function bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class, 'bake_bread_size_id');
    }
}
