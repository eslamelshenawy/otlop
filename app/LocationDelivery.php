<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationDelivery extends Model
{
    protected $table = 'location_deliveries';

    protected $guarded = [];

    public function deliveryMan()
    {
        return $this->belongsTo(Admin::class)
            ->where('userType','=','delivery');
    }
}
