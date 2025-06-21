<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickUpReminder extends Model
{
    //
    protected $table = 'pickup_reminders';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'pickup_request_id',
        'reminder_time',
        'sent',
        'soft_delete',
    ];
}
