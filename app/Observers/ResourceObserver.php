<?php

namespace App\Observers;

use App\Models\Resource;
use App\ChangesLogHelper;

class ResourceObserver
{
    /**
     * Handle the Resource "created" event.
     *
     * @param  \App\Models\Resource  $resource
     * @return void
     */
    public function created(Resource $resource)
    {
        $data = array(
            'element_name' => $resource->name,
            'element_key' => $resource->resource_key,
            'model' => 'Recursos',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the Resource "updated" event.
     *
     * @param  \App\Models\Resource  $resource
     * @return void
     */
    public function updated(Resource $resource)
    {
        $data = array(
            'element_name' => $resource->name,
            'element_key' => $resource->resource_key,
            'model' => 'Recursos',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
