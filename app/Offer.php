<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';

    protected $guarded = [];

    public function mealOffers()
    {
        $this->hasOne(MenuDetails::class);
    }
}
