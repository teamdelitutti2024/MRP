<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLevel2 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_level_2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'bake_bread_size_id',
    ];

    public function bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class);
    }

    public function stock_level_2_details()
    {
        return $this->hasMany(StockLevel2Detail::class, 'stock_level_2_id');
    }
}
