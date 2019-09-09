<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['keyword','description'];

    protected $guarded =[];
}
