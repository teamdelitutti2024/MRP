<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProduction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products_production';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'responsible_id', 'reversed_responsible_id', 'status',
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function reversed_responsible()
    {
        return $this->belongsTo(User::class, 'reversed_responsible_id');
    }

    public function production_details()
    {
        return $this->hasMany(ProductProductionDetail::class, 'products_production_id');
    }

    public function production_supplies()
    {
        return $this->hasMany(ProductProductionLog::class, 'production_id');
    }

    public function production_bake_breads()
    {
        return $this->hasMany(ProductProductionBakeBreadLog::class, 'production_id');
    }
}
