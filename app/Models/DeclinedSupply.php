<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclinedSupply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply', 'lost_quantity', 'transaction_amount', 'category', 'reason', 'status', 'disabled_date', 'supply_id', 'measurement_unit_id', 'enabled_responsible_id', 'disabled_responsible_id', 'reversed_responsible_id', 'quarantine_id', 'supply_location_id',
    ];

    public function sup()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function enabled_responsible()
    {
        return $this->belongsTo(User::class, 'enabled_responsible_id');
    }

    public function disabled_responsible()
    {
        return $this->belongsTo(User::class, 'disabled_responsible_id');
    }

    public function reversed_responsible()
    {
        return $this->belongsTo(User::class, 'reversed_responsible_id');
    }

    public function quarantine()
    {
        return $this->belongsTo(Quarantine::class);
    }

    public function supply_location()
    {
        return $this->belongsTo(SupplyLocation::class);
    }
}
