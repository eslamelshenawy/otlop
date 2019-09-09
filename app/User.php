<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName','lastName', 'email', 'password','phone','address','image','status','city_id'
        ,'provider', 'provider_id','facebook_id'
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

    public function routeNotificationForFcm() {
        //return a device token, either from the model or from some other place.
        return $this->device_token;
    }
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
        return asset('upload/users/'.$this->image);

    }


    public function userWallets()
    {
        return $this->hasOne(Wallet::class,'user_id','id');
    }

    public function chargingWallets()
    {
        return $this->belongsToMany(ChargingWallet::class);
    }

    public function UserWalletDetails()
    {
        return $this->hasMany(UserWalletDetails::class);
    }

    public function addNew($input)
    {
        $check = static::where('facebook_id',$input['facebook_id'])->first();


        if(is_null($check)){
            return static::create($input);
        }


        return $check;
    }


}
