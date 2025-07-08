<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartureShipmentDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name', 'product_size_name', 'quantity', 'requested_quantity', 'received_quantity', 'price', 'product_id', 'departure_shipment_id', 'product_size_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function departure_shipment()
    {
        return $this->belongsTo(DepartureShipment::class);
    }

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }
}
