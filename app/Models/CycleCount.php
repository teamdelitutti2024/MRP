<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CycleCount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'type', 'responsible_id',
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function cycle_count_details()
    {
        return $this->hasMany(CycleCountDetail::class);
    }
}
