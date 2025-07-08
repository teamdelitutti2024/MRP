<?php

namespace App\Observers;

use App\Models\MeasurementUnit;
use App\ChangesLogHelper;

class MeasurementUnitObserver
{
    /**
     * Handle the MeasurementUnit "created" event.
     *
     * @param  \App\Models\MeasurementUnit  $measurement_unit
     * @return void
     */
    public function created(MeasurementUnit $measurement_unit)
    {
        $data = array(
            'element_name' => $measurement_unit->name,
            'element_key' => $measurement_unit->id,
            'model' => 'Unidades de medida',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the MeasurementUnit "updated" event.
     *
     * @param  \App\Models\MeasurementUnit  $measurement_unit
     * @return void
     */
    public function updated(MeasurementUnit $measurement_unit)
    {
        $data = array(
            'element_name' => $measurement_unit->name,
            'element_key' => $measurement_unit->id,
            'model' => 'Unidades de medida',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
