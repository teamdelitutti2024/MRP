<?php

namespace App\Observers;

use App\Models\ProductSize;
use App\ChangesLogHelper;

class ProductSizeObserver
{
    /**
     * Handle the ProductSize "created" event.
     *
     * @param  \App\Models\ProductSize  $product_size
     * @return void
     */
    public function created(ProductSize $product_size)
    {
        $data = array(
            'element_key' => $product_size->product_size_key,
            'element_name' => $product_size->name,
            'model' => 'Tamaño de productos',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the ProductSize "updated" event.
     *
     * @param  \App\Models\ProductSize  $product_size
     * @return void
     */
    public function updated(ProductSize $product_size)
    {
        $data = array(
            'element_key' => $product_size->product_size_key,
            'element_name' => $product_size->name,
            'model' => 'Tamaño de productos',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
