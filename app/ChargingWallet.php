<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargingWallet extends Model
{
    protected $table = 'charging_wallets';

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
