<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function stateTranslatable()
    {
        return $this->hasMany(StateTranslation::class);
    }

}
