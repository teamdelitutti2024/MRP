<?php

namespace App\Observers;

use App\Models\SupplyReception;
use App\ChangesLogHelper;

class SupplyReceptionObserver
{
    /**
     * Handle the SupplyReception "created" event.
     *
     * @param  \App\Models\SupplyReception  $supply_reception
     * @return void
     */
    public function created(SupplyReception $supply_reception)
    {
        $data = array(
            'element_key' => $supply_reception->id,
            'model' => 'Recepciones de materia prima',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the SupplyReception "updated" event.
     *
     * @param  \App\Models\SupplyReception  $supply_reception
     * @return void
     */
    public function updated(SupplyReception $supply_reception)
    {
        $data = array(
            'element_key' => $supply_reception->id,
            'model' => 'Recepciones de materia prima',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
