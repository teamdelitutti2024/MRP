<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSizeResource extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_time', 'quantity_to_produce', 'product_size_id', 'resource_id',
    ];

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
