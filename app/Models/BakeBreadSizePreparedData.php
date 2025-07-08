<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakeBreadSizePreparedData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bake_bread_size_prepared_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'bake_bread_size_id', 'prepared_product_id',
    ];

    public function bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class);
    }

    public function prepared_product()
    {
        return $this->belongsTo(PreparedProduct::class);
    }
}
