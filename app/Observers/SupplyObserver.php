<?php

namespace App\Observers;

use App\Models\Supply;
use App\ChangesLogHelper;

class SupplyObserver
{
    /**
     * Handle the Supply "created" event.
     *
     * @param  \App\Models\Supply  $supply
     * @return void
     */
    public function created(Supply $supply)
    {
        $data = array(
            'element_name' => $supply->name,
            'element_key' => $supply->supply_key,
            'model' => 'Materias primas',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the Supply "updated" event.
     *
     * @param  \App\Models\Supply  $supply
     * @return void
     */
    public function updated(Supply $supply)
    {
        $data = array(
            'element_name' => $supply->name,
            'element_key' => $supply->supply_key,
            'model' => 'Materias primas',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
