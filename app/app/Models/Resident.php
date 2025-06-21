<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
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
