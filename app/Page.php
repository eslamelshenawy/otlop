<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name','title','description'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    protected $appends = ['imagePath'];
    public function getImagePathAttribute()
    {
        return asset('upload/page/'.$this->image);

    }


}
