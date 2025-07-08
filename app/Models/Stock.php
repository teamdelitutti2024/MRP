<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'supply_id', 'supply_location_id',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function supply_location()
    {
        return $this->belongsTo(SupplyLocation::class);
    }
}
