<?php

namespace App\Observers;

use App\Models\CycleCount;
use App\ChangesLogHelper;

class CycleCountObserver
{
    /**
     * Handle the CycleCount "created" event.
     *
     * @param  \App\Models\CycleCount  $cycle_count
     * @return void
     */
    public function created(CycleCount $cycle_count)
    {
        $data = array(
            'element_key' => $cycle_count->id,
            'model' => 'Conteos cíclicos',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the CycleCount "updated" event.
     *
     * @param  \App\Models\CycleCount  $cycle_count
     * @return void
     */
    public function updated(CycleCount $cycle_count)
    {
        $data = array(
            'element_key' => $cycle_count->id,
            'model' => 'Conteos cíclicos',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
