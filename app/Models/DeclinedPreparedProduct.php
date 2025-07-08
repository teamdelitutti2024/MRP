<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclinedPreparedProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'prepared_product_name', 'comments', 'status', 'prepared_product_id', 'responsible_id', 'reversed_responsible_id',
    ];

    public function prepared_product()
    {
        return $this->belongsTo(PreparedProduct::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function reversed_responsible()
    {
        return $this->belongsTo(User::class, 'reversed_responsible_id');
    }

    public function prepared_product_supplies()
    {
        return $this->hasMany(DeclinedPreparedProductLog::class, 'declined_pp_id');
    }
}
