<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierTaxData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_reason', 'RFC', 'street', 'outside_number', 'inside_number', 'colony', 'zip_code', 'city', 'state', 'country', 'supplier_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
