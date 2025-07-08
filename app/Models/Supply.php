<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
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
        'supply_key', 'name', 'standard_pack', 'unit', 'measurement_unit_id', 'supply_category_id', 'average_cost', 'initial_cost', 'min_stock', 'max_stock', 'reorder_stock', 'safety_stock', 'is_active', 'stock', 'requires_iva', 'requires_ieps',
    ];
    
    public function ingredients()
    {
        return $this->belongsToMany(ProductSize::class, 'ingredients', 'supply_id', 'product_size_id')
                    ->using(Ingredient::class)
                    ->withPivot([
                        'id',
                        'quantity',
                        'created_at',
                        'updated_at',
                        'measurement_unit_id',
                    ]);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_supplies', 'supply_id', 'supplier_id')
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

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function supply_category()
    {
        return $this->belongsTo(SupplyCategory::class);
    }
}
