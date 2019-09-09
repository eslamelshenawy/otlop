<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $table = 'trips';

    protected $guarded = [];

    public function drivers_in_dispatching(){
        return $this->belongsToMany(Admin::class,'trip_driver_dispatches','trip_id','driver_id')->withPivot('request_sent_status')->withTimestamps();
    }
    public function trip_main_statuses(){
        return $this->belongsToMany(TripStatus::class,'trip_status_logs','trip_id','trip_statuses_id')->withTimestamps();
    }

    public function trip_timing(){
        return $this->hasOne(TripTiming::class);
    }

    public function dispatcher(){
        return $this->hasMany(TripDriverDispatches::class,'trip_id');
    }

    public function trip_driver_statuses(){
        return $this->belongsToMany(TripDriverStatus::class,'trip_driver_status_logs','trip_id','trip_driver_statuses_id')->withTimestamps();
    }


}
