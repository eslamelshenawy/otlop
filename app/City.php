<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function cityTranslatable()
    {
        return $this->hasMany(CityTranslation::class);
    }
}
