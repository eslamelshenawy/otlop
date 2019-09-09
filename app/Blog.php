<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['title','description'];

    protected $guarded =[];
    protected $appends = ['imagePath'];
    public function getImagePathAttribute()
    {
        return asset('upload/blog/'.$this->image);

    }
    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
    public function blogTranslation()
    {
        return $this->hasMany(BlogTranslation::class);
    }
    public function getRouteKeyName()
    {
        return 'title';
    }
}
