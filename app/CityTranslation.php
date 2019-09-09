<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $table = 'city_translations';

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function city_neme($id){
    dd('sds');
    }
}
