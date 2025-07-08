<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLevel1Detail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_level_1_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'last_quantity', 'valid_until', 'reason', 'stock_level_1_id', 'responsible_id',
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
