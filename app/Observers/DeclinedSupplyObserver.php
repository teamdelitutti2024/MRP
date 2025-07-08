<?php

namespace App\Observers;

use App\Models\DeclinedSupply;
use App\ChangesLogHelper;

class DeclinedSupplyObserver
{
    /**
     * Handle the DeclinedSupply "created" event.
     *
     * @param  \App\Models\DeclinedSupply  $declined_supply
     * @return void
     */
    public function created(DeclinedSupply $declined_supply)
    {
        $data = array(
            'element_key' => $declined_supply->id,
            'model' => 'Mermas',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the DeclinedSupply "updated" event.
     *
     * @param  \App\Models\DeclinedSupply  $declined_supply
     * @return void
     */
    public function updated(DeclinedSupply $declined_supply)
    {
        $data = array(
            'element_key' => $declined_supply->id,
            'model' => 'Mermas',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
