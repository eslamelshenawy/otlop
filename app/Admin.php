<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable,HasApiTokens;

    protected $fillable = [
        'firstName', 'lastName', 'email', 'phone','userType',
        'address', 'image', 'status', 'user_token','userType','created_by','updated_by','parent_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['imagePath'];

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getImagePathAttribute()
    {
        return asset('upload/admin/'.$this->image);

    }

    public function getFullNameAttribute() {
        return ucfirst($this->firstName). ' ' . ucfirst($this->lastName);
    }

    public function deliveryWallet()
    {
        $this->hasOne(WalletDelivery::class,'delivery_id','id');
    }

    public function locationDelivery()
    {
        return $this->hasOne(LocationDelivery::class);
    }
    public function trips_in_queue(){
        return $this->belongsToMany(Trip::class,'trip_driver_dispatches','driver_id','trip_id')->withPivot('rank','request_sent_status')->withTimestamps();
    }

}
