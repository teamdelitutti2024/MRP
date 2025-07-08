<?php

namespace App\Observers;

use App\Models\BakeBreadSize;
use App\ChangesLogHelper;

class BakeBreadSizeObserver
{
    /**
     * Handle the BakeBreadSize "created" event.
     *
     * @param  \App\Models\BakeBreadSize  $bake_bread_size
     * @return void
     */
    public function created(BakeBreadSize $bake_bread_size)
    {
        $data = array(
            'element_name' => $bake_bread_size->name,
            'element_key' => $bake_bread_size->bake_bread_size_key,
            'model' => 'Tamaño de bases',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the BakeBreadSize "updated" event.
     *
     * @param  \App\Models\BakeBreadSize  $bake_bread_size
     * @return void
     */
    public function updated(BakeBreadSize $bake_bread_size)
    {
        $data = array(
            'element_name' => $bake_bread_size->name,
            'element_key' => $bake_bread_size->bake_bread_size_key,
            'model' => 'Tamaño de bases',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
