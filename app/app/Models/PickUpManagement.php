<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickUpManagement extends Model
{
    //
    protected $table = 'pickup_management';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'pick_up_name',
        'reg_number',
        'added_by',
        'soft_delete',
    ];
}
