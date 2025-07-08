<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundShipmentDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name', 'quantity', 'status', 'justification', 'product_id', 'inbound_shipment_id', 'product_size_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inbound_shipment()
    {
        return $this->belongsTo(InboundShipment::class);
    }

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }
}
