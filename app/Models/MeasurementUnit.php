<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'measure',
    ];

    public function equivalences()
    {
        return $this->hasMany(Equivalence::class, 'source_measurement_id');
    }
}
