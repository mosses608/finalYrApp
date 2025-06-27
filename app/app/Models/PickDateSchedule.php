<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickDateSchedule extends Model
{
    //
    protected $table = 'pick_up_date_schedule';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'pickup_day',
        'preferred_time',
        'staff_id',
        'pick_up_id',
        'location',
        'soft_delete',
    ];
}
