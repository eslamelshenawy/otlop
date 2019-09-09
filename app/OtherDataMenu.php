<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherDataMenu extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['title'];

    protected $guarded =[];

    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
}
