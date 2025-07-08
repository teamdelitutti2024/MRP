<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLevel1 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_level_1';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'product_id', 'product_size_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function stock_level_1_details()
    {
        return $this->hasMany(StockLevel1Detail::class, 'stock_level_1_id');
    }
}
