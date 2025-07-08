<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartureShipment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipment_date', 'total', 'status', 'order_id', 'responsible_id', 'branch_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function departure_shipment_details()
    {
        return $this->hasMany(DepartureShipmentDetail::class);
    }

    public function inbound_shipments()
    {
        return $this->hasMany(InboundShipment::class);
    }
}
