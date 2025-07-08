<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakeBreadSizeBakeBread extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'bake_bread_size_id', 'related_bake_bread_size_id',
    ];

    public function bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class, 'bake_bread_size_id');
    }

    public function related_bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class, 'related_bake_bread_size_id');
    }
}
