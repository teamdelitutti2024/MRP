<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'total', 'delivery_date', 'status', 'commercial_term_default', 'require_invoice', 'preferred_payment_method', 'supplier_id', 'responsible_id', 'commercial_term_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function commercial_term()
    {
        return $this->belongsTo(CommercialTerm::class);
    }

    public function supply_order_details()
    {
        return $this->hasMany(SupplyOrderDetail::class);
    }

    public function supply_receptions()
    {
        return $this->hasMany(SupplyReception::class);
    }
}
