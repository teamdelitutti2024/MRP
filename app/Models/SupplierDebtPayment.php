<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierDebtPayment extends Model
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
        'payment_date', 'amount', 'payment_method', 'user_id', 'supplier_debt_id',
    ];

    public function supplier_debt()
    {
        return $this->belongsTo(SupplierDebt::class);
    }
}
