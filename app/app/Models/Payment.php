<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'pick_up_id',
        'user_email',
        'payment_id',
        'payer_id',
        'payer_email',
        'amount',
        'currency',
        'status',
        'soft_delete',
    ];
}
