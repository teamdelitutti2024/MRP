<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundShipment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'received_date', 'departure_shipment_id', 'responsible_id', 'branch_id',
    ];

    public function departure_shipment()
    {
        return $this->belongsTo(DepartureShipment::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function inbound_shipment_details()
    {
        return $this->hasMany(InboundShipmentDetail::class);
    }
}
