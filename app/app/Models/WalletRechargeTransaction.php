<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletRechargeTransaction extends Model
{
    //
    protected $table = 'wallet_recharge_transaction';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'payment_id',
        'user_id',
        'payer_id',
        'payer_email',
        'amount',
        'currency',
        'status',
        'soft_delete',
    ];
}
