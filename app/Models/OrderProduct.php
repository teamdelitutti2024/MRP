<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
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
        'quantity', 'price', 'total_price', 'product_size_id', 'order_id', 'product_id', 'quantity_sold', 'code', 'quantity_decrease', 'quantity_tasting', 'sliced_quantity', 'slices_quantity', 'slices_quantity_sold', 'refund_quantity', 'slices_quantity_decreased',
    ];

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
