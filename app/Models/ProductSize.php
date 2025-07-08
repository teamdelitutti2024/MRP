<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'product_size_key', 'products_minimum_amount', 'price', 'sale_price', 'complexity', 'product_id', 'older_price',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function prepared_product_data()
    {
        return $this->hasMany(ProductSizePreparedData::class);
    }

    public function resources()
    {
        return $this->hasMany(ProductSizeResource::class);
    }

    public function bake_breads()
    {
        return $this->hasMany(ProductSizeBakeBread::class);
    }
}
