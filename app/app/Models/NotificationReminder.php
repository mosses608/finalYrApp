<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationReminder extends Model
{
    //
    protected $table = 'notifications_reminders';
    protected $primar = 'id';
    public $incrementing = true;

    protected $fillable = [
        'email',
        'title',
        'message_body',
        'sent_by',
    ];
}
