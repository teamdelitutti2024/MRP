<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyReception extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'total', 'supply_order_id', 'responsible_id',
    ];

    public function supply_order()
    {
        return $this->belongsTo(SupplyOrder::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function supply_reception_details()
    {
        return $this->hasMany(SupplyReceptionDetail::class);
    }
}
