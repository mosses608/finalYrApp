<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Resident extends Model
{
    use Notifiable;
    //
    protected $table = 'residents';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'picture',
    ];
}
