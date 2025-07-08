<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierBankData extends Model
{    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank', 'account_holder', 'account_number', 'clabe', 'supplier_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
