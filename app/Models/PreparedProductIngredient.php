<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparedProductIngredient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'quantity_to_produce', 'prepared_product_id', 'supply_id', 'measurement_unit_id', 'supply_location_id',
    ];

    public function prepared_product()
    {
        return $this->belongsTo(PreparedProduct::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
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
