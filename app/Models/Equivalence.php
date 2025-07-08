<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equivalence extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'equivalence', 'source_measurement_id', 'target_measurement_id',
    ];

    public function source_measurement()
    {
        return $this->belongsTo(MeasurementUnit::class, 'source_measurement_id');
    }

    public function target_measurement()
    {
        return $this->belongsTo(MeasurementUnit::class, 'target_measurement_id');
    }
}
