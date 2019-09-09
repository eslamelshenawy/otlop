<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function typeTranslatable()
    {
        return $this->hasMany(TypeTranslation::class);
    }

    public function restaurantType()
    {
        return $this->hasMany(Restaurant::class);
    }
}
