<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $table = 'locations';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'phone_number',
        'latitude',
        'longitude',
    ];
}
