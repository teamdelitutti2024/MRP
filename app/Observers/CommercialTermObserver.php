<?php

namespace App\Observers;

use App\Models\CommercialTerm;
use App\ChangesLogHelper;

class CommercialTermObserver
{
    /**
     * Handle the CommercialTerm "created" event.
     *
     * @param  \App\Models\CommercialTerm  $commercial_term
     * @return void
     */
    public function created(CommercialTerm $commercial_term)
    {
        $data = array(
            'element_name' => $commercial_term->name,
            'element_key' => $commercial_term->id,
            'model' => 'Condiciones comerciales',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the CommercialTerm "updated" event.
     *
     * @param  \App\Models\CommercialTerm  $commercial_term
     * @return void
     */
    public function updated(CommercialTerm $commercial_term)
    {
        $data = array(
            'element_name' => $commercial_term->name,
            'element_key' => $commercial_term->id,
            'model' => 'Condiciones comerciales',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
