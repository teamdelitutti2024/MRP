<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakeBreadSizeResource extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_time', 'quantity_to_produce', 'bake_bread_size_id', 'resource_id',
    ];

    public function bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
