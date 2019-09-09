<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissonExchange extends Model
{
    //
    use \Dimsav\Translatable\Translatable;
    public $translatedAttributes = ['name','address'];

    protected $table = 'permisson_exchange';

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
