<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function categoryTranslation()
    {
        return $this->hasMany(CategoryTranslation::class)
            ->where('locale',\App::getLocale());
    }

    public function FQACategory()
    {
        return $this->hasMany(Question::class);
    }
}
