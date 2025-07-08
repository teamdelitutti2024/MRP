<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SupplierSupply extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'supplier_supplies';

    public $incrementing = true;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price', 'cost', 'supply_id', 'supplier_id', 'measurement_unit_id',
    ];

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }
}
