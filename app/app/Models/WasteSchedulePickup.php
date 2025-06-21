<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteSchedulePickup extends Model
{
    //
    protected $table = 'waste_schedule_pickup';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'frequency',
        'pickup_date',
        'preferred_time',
        'location',
        'status',
        'soft_delete',
    ];
}
