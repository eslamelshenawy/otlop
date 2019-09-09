<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name','description'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
    protected $appends = ['imagePath'];
    public function getImagePathAttribute()
    {
        return asset('upload/menu/'.$this->image);

    }

    public function menuDetailsRestaurant()
    {
        return $this->hasMany(MenuDetails::class);
    }

    public function menuTranslatable()
    {
        return $this->hasMany(MenuTranslation::class);
    }
}
