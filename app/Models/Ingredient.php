<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Ingredient extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients';

    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'quantity_to_produce', 'supply_id', 'product_size_id', 'measurement_unit_id', 'supply_location_id',
    ];

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function supply_location()
    {
        return $this->belongsTo(SupplyLocation::class);
    }
}
