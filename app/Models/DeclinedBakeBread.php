<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclinedBakeBread extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'bake_bread_date', 'bake_bread_size_name', 'comments', 'status', 'bake_bread_size_id', 'responsible_id', 'reversed_responsible_id',
    ];

    public function bake_bread_size()
    {
        return $this->belongsTo(BakeBreadSize::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function reversed_responsible()
    {
        return $this->belongsTo(User::class, 'reversed_responsible_id');
    }
}
