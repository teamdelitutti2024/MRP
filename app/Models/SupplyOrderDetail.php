<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrderDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply', 'quantity', 'cost', 'price', 'received_quantity', 'supply_id', 'measurement_unit_id', 'supply_order_id',
    ];

    public function sup()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function supply_order()
    {
        return $this->belongsTo(SupplyOrder::class);
    }
}
