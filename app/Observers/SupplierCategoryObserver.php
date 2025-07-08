<?php

namespace App\Observers;

use App\Models\SupplierCategory;
use App\ChangesLogHelper;

class SupplierCategoryObserver
{
    /**
     * Handle the SupplierCategory "created" event.
     *
     * @param  \App\Models\SupplierCategory  $supplier_category
     * @return void
     */
    public function created(SupplierCategory $supplier_category)
    {
        $data = array(
            'element_name' => $supplier_category->name,
            'element_key' => $supplier_category->id,
            'model' => 'Categorías de proveedores',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the SupplierCategory "updated" event.
     *
     * @param  \App\Models\SupplierCategory  $supplier_category
     * @return void
     */
    public function updated(SupplierCategory $supplier_category)
    {
        $data = array(
            'element_name' => $supplier_category->name,
            'element_key' => $supplier_category->id,
            'model' => 'Categorías de proveedores',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
