<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    //
    protected $table = 'contracts';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'recyclable_id',
        'buyer_id',
        'seller_id',
        'price_usd',
        'status',
        'blockchain_data',
    ];
}
