<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactInformation extends Model
{
    protected $table = 'contact_information';

    protected $fillable =
        [
          'email','phone','mobile','support_call','address' ,
        ];
}
