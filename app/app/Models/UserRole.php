<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name','slug'
    ];
}
