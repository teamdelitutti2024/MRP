<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyReceptionDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'supply_reception_id', 'supply_order_detail_id',
    ];

    public function supply_reception()
    {
        return $this->belongsTo(SupplyReception::class);
    }

    public function supply_order_detail()
    {
        return $this->belongsTo(SupplyOrderDetail::class);
    }

    public function declined_supply()
    {
        return $this->hasOne(DeclinedSupply::class);
    }

    public function quarantine()
    {
        return $this->hasOne(Quarantine::class);
    }

    public function cycle_count_details()
    {
        return $this->hasMany(CycleCountDetail::class);
    }
}
