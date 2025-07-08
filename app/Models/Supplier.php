<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MeasurementUnit;

class Supplier extends Model
{   
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'supplier_key', 'address', 'notes', 'delivery_time', 'payment_information', 'preferred_payment_method', 'supplier_category_id', 'commercial_term_id', 'require_invoice',
    ];

    public function commercial_term()
    {
        return $this->belongsTo(CommercialTerm::class);
    }

    public function category()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_category_id');
    }

    public function supplies()
    {
        return $this->belongsToMany(Supply::class, 'supplier_supplies', 'supplier_id', 'supply_id')
                    ->as('association')
                    ->using(SupplierSupply::class)
                    ->withPivot([
                        'id',
                        'price',
                        'cost',
                        'measurement_unit_id',
                        'created',
                        'modified',
                    ]);
    }

    public function contacts()
    {
        return $this->hasMany(SupplierContact::class);
    }

    public function bank_data()
    {
        return $this->hasMany(SupplierBankData::class);
    }
    
    public function tax_data()
    {
        return $this->hasOne(SupplierTaxData::class);
    }
}
