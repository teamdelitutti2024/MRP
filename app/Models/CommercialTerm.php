<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommercialTerm extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'deposit', 'payments_detail'
    ];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
