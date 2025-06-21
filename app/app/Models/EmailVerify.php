<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerify extends Model
{
    //
    protected $table = 'email_verify';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'email',
        'token',
        'expired',
    ];
}
