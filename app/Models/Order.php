<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
        'total', 'delivery_date', 'status', 'payment', 'payment_method', 'payment_date', 'branch_id', 'balance_used', 'discount',
    ];

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function departure_shipment()
    {
        return $this->hasOne(DepartureShipment::class);
    }
}
