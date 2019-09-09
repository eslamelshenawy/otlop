<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestWorking extends Model
{
    protected $table = 'request_workings';

    protected $guarded = [];

    public function city()
    {
        return $this->hasMany(City::class);
    }
    public function state()
    {
        return $this->hasMany(State::class);
    }
}
