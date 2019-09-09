<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable =
        [
            'user_id','account','status'
        ];

    public function walletsUser()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
