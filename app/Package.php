<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function packageTranslatable()
    {
        return $this->hasMany(PackageTranslation::class);
    }
}
