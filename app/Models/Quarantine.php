<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quarantine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply', 'quantity', 'transaction_amount', 'category', 'reason', 'status', 'disabled_date', 'change_to_decreased_date', 'supply_id', 'measurement_unit_id', 'enabled_responsible_id', 'disabled_responsible_id', 'change_to_decreased_responsible_id', 'supply_location_id',
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

    public function change_to_decreased_responsible()
    {
        return $this->belongsTo(User::class, 'change_to_decreased_responsible_id');
    }

    public function declined_supply()
    {
        return $this->hasOne(DeclinedSupply::class);
    }

    public function supply_location()
    {
        return $this->belongsTo(SupplyLocation::class);
    }
}
