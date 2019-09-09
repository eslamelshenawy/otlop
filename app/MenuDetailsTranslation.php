<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuDetailsTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','description'];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
