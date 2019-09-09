<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletDelivery extends Model
{
    protected $table = 'wallet_deliveries';

    protected $fillable =
        [
            'delivery_id','account','date','shift','status'
        ];

    public function walletDelivery()
    {
        $this->belongsTo(Admin::class,'delivery_id','id');
    }
}
