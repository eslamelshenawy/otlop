<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['keyword','description'];
}
