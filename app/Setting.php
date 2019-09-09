<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name','description'];

    protected $guarded =[];

    protected $appends = ['logoPath','iconPath'];

    public function getLogoPathAttribute()
    {
        return asset('upload/setting/'.$this->logo);

    }

    public function getIconPathAttribute()
    {
        return asset('upload/setting/'.$this->icon);

    }
}
