<?php

namespace App\Observers;

use App\Models\Quarantine;
use App\ChangesLogHelper;

class QuarantineObserver
{
    /**
     * Handle the Quarantine "created" event.
     *
     * @param  \App\Models\Quarantine  $quarantine
     * @return void
     */
    public function created(Quarantine $quarantine)
    {
        $data = array(
            'element_key' => $quarantine->id,
            'model' => 'Cuarentenas',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the Quarantine "updated" event.
     *
     * @param  \App\Models\Quarantine  $quarantine
     * @return void
     */
    public function updated(Quarantine $quarantine)
    {
        $data = array(
            'element_key' => $quarantine->id,
            'model' => 'Cuarentenas',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
