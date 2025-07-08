<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
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
        'name', 'address', 'status', 'balance', 'code', 'opening_date', 'phone', 'latitude', 'longitude', 'allow_mobile_sales', 'is_cedis', 'branch_royalty', 'pay_branch_delayed', 'first_payment_date', 'total_amount', 'number_of_payments', 'route', 'is_special', 'show_in_list', 'sale_code',
    ];
}
