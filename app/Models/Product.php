<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        'product_order', 'name', 'status', 'is_cake', 'expiry_days', 'photo', 'mobile_order', 'category_id', 'description',
    ];

    public function product_sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
