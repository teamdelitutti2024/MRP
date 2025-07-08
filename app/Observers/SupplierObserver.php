<?php

namespace App\Observers;

use App\Models\Supplier;
use App\ChangesLogHelper;

class SupplierObserver
{
    /**
     * Handle the Supplier "created" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function created(Supplier $supplier)
    {
        $data = array(
            'element_name' => $supplier->name,
            'element_key' => $supplier->supplier_key,
            'model' => 'Proveedores',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the Supplier "updated" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function updated(Supplier $supplier)
    {
        $data = array(
            'element_name' => $supplier->name,
            'element_key' => $supplier->supplier_key,
            'model' => 'Proveedores',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
