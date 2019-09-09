<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = [];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }


}
