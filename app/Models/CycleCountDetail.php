<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CycleCountDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply', 'stock_quantity', 'counted_quantity', 'comments', 'cycle_count_id', 'supply_id', 'measurement_unit_id', 'supply_location_id',
    ];

    public function cycle_count()
    {
        return $this->belongsTo(CycleCount::class);
    }

    public function sup()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function supply_location()
    {
        return $this->belongsTo(SupplyLocation::class);
    }
}
