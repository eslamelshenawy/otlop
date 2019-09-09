<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendShare extends Model
{
    protected $table = 'send_shares';

    protected $guarded = [];


    public function  getusers(){

        dd($id);
    }

}
