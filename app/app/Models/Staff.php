<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    //
    protected $table = 'staff';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'names',
        'email',
        'phone_number',
        'role',
        'gender',
        'date_of_birth',
        'photo',
        'is_active',
        'soft_delete',
    ];
}
