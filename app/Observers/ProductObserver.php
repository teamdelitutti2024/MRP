<?php

namespace App\Observers;

use App\Models\Product;
use App\ChangesLogHelper;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        $data = array(
            'element_name' => $product->name,
            'element_key' => $product->id,
            'model' => 'Productos',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $data = array(
            'element_name' => $product->name,
            'element_key' => $product->id,
            'model' => 'Productos',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
