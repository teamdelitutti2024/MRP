<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProductionDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products_production_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name', 'product_size_name', 'quantity', 'price', 'product_id', 'product_size_id', 'products_production_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }
}
