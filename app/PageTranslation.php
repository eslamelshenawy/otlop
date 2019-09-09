<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','title','description'];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
