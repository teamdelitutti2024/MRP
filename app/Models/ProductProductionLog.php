<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProductionLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products_production_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply_id', 'supply_key', 'supply', 'supply_location_id', 'supply_location', 'quantity', 'measurement_unit_id', 'measure', 'average_cost', 'production_id',
    ];
}
