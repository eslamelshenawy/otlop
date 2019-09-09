<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'vendor_id', 'restaurant_id', 'city_id', 
        'state_id', 'address', 'lat', 'lng','created_by','updated_by'
    ];

    public function cityLocation()
    {
        return $this->hasMany(City::class);
    }

    public function stateLocation()
    {
        return $this->hasMany(State::class);
    }


}
