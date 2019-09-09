<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherDataMenuTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];

    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
}
