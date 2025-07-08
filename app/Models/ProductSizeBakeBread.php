<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSizeBakeBread extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'product_size_id', 'bake_bread_size_id',
    ];

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function bake_bread()
    {
        return $this->belongsTo(BakeBreadSize::class, 'bake_bread_size_id');
    }
}
