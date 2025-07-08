<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclinedProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'product_date', 'product_name', 'product_size_name', 'price', 'comments', 'status', 'product_id', 'product_size_id', 'responsible_id', 'reversed_responsible_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function reversed_responsible()
    {
        return $this->belongsTo(User::class, 'reversed_responsible_id');
    }
}
