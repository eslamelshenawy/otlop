<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['title','description'];

    protected $guarded =[];

    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
    public function questionTranslation()
    {
        return $this->hasMany(QuestionTranslation::class)
            ->where('locale',\App::getLocale());
    }

    public function categoryQuestion()
    {
        return $this->belongsTo(Category::class);
    }
}
