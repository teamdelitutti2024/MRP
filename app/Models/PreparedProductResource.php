<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparedProductResource extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_time', 'quantity_to_produce', 'prepared_product_id', 'resource_id',
    ];

    public function prepared_product()
    {
        return $this->belongsTo(PreparedProduct::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
