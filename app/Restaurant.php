<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name','address'];

    protected $guarded =[];

    protected $appends = ['imagePath','logoPath'];


    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getImagePathAttribute()
    {
        return asset('upload/restaurant/'.$this->image);

    }
    public function getLogoPathAttribute()
    {
        return asset('upload/restaurant/'.$this->logo);

    }
    public function admin()
    {
        return $this->hasOne(Admin::class ,'id','admin_id');
    }

    #slug route

    public function getRouteKeyName()
    {
        return 'restaurant';
    }

    #relation Restaurant with Restaurant Translations
    public function restaurantTranslations()
    {
        return $this->hasMany(RestaurantTranslation::class);
    }
    #relation Restaurant with menu
    public function menuRestaurant()
    {
        return $this->hasMany(Menu::class);
    }

    public function ratingRestaurant()
    {
        return $this->hasMany(RatingRestaurant::class);
    }
    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function offers()
    {
       return $this->hasMany(Offer::class);
    }

    public function locationRestaurant()
    {
        return $this->hasMany(Location::class);
    }

    public function restaurantType()
    {
        return $this->belongsTo(Type::class);
    }
    #most-selling
    public function orders()
    {
        return $this->hasMany(Order::class);
    }



}
