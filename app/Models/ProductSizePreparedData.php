<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSizePreparedData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_size_prepared_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'product_size_id', 'prepared_product_id',
    ];

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function prepared_product()
    {
        return $this->belongsTo(PreparedProduct::class);
    }
}
