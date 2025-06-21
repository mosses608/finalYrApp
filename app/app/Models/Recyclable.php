<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recyclable extends Model
{
    //
    protected $table = 'recyclables';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_id','title',
        'material_type',
        'weight',
        'price','image',
        'description','soft_delete',
    ];
}
