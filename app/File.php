<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded =[];

    protected $appends = ['imagePath'];

    public function getImagePathAttribute()
    {
        return asset('upload/file/'.$this->name);

    }
}
