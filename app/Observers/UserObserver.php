<?php

namespace App\Observers;

use App\Models\User;
use App\ChangesLogHelper;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $data = array(
            'element_name' => $user->name,
            'element_key' => $user->id,
            'model' => 'Usuarios',
            'event' => 'CREACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $data = array(
            'element_name' => $user->name,
            'element_key' => $user->id,
            'model' => 'Usuarios',
            'event' => 'ACTUALIZACIÓN',
            'responsible_id' => auth()->user()->id,
        );

        ChangesLogHelper::add($data);
    }
}
