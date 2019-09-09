<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable =
        [
            'emailTo','name','emailSend','subject','message','read'
            ,'send','receive','mobile','phone'
        ];
}
