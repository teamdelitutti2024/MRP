<?php

namespace App\Observers;

use App\Models\PreparedProduct;
use App\ChangesLogHelper;

class PreparedProductObserver
{
    /**
     * Handle the PreparedProduct "created" event.
     *
     * @param  \App\Models\PreparedProduct  $prepared_product
     * @return void
     */
    public function created(PreparedProduct $prepared_product)
    {
        $data = array(
            'element_name' => $prepared_product->name,
            'element_key' => $prepared_product->product_key,
            'model' => 'Preparados',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the PreparedProduct "updated" event.
     *
     * @param  \App\Models\PreparedProduct  $prepared_product
     * @return void
     */
    public function updated(PreparedProduct $prepared_product)
    {
        $data = array(
            'element_name' => $prepared_product->name,
            'element_key' => $prepared_product->product_key,
            'model' => 'Preparados',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
