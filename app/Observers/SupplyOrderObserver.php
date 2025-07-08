<?php

namespace App\Observers;

use App\Models\SupplyOrder;
use App\ChangesLogHelper;

class SupplyOrderObserver
{
    /**
     * Handle the SupplyOrder "created" event.
     *
     * @param  \App\Models\SupplyOrder  $supply_order
     * @return void
     */
    public function created(SupplyOrder $supply_order)
    {
        $data = array(
            'element_key' => $supply_order->id,
            'model' => 'Pedidos de materia prima',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the SupplyOrder "updated" event.
     *
     * @param  \App\Models\SupplyOrder  $supply_order
     * @return void
     */
    public function updated(SupplyOrder $supply_order)
    {
        $data = array(
            'element_key' => $supply_order->id,
            'model' => 'Pedidos de materia prima',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
