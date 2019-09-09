<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuDetails extends Model
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
        return asset('upload/meal/'.$this->image);

    }

    public function otherDataMenu()
    {
        return $this->hasMany(OtherDataMenu::class);
    }

    public function offer()
    {
        return $this->hasMany(Offer::class);
    }

    public function menuDetailsTranslatable()
    {
        return $this->hasMany(MenuDetailsTranslation::class);
    }
}
