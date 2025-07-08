<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'changes_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'element_name', 'element_key', 'model', 'event', 'previous_quantity', 'new_quantity', 'responsible_id'
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
