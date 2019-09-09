<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['title','description'];

    protected $guarded =[];

    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
}
