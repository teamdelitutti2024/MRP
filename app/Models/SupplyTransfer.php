<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyTransfer extends Model
{   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply', 'transferred_quantity', 'transaction_amount', 'comment', 'supply_id', 'measurement_unit_id', 'source_location_id', 'destination_location_id', 'responsible_id',
    ];

    public function sup()
    {
        return $this->belongsTo(Supply::class);
    }

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function source_location()
    {
        return $this->belongsTo(SupplyLocation::class, 'source_location_id');
    }

    public function destination_location()
    {
        return $this->belongsTo(SupplyLocation::class, 'destination_location_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
