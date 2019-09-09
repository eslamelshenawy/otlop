<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripDriverDispatches extends Model
{
    protected $table ='trip_driver_dispatches';

    protected $fillable=["request_sent_status","request_sent_at"];
}
