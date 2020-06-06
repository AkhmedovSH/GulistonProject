<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class User extends Authenticatable implements JWTSubject
{
    /* User types column type
            0 - simple user
            1 - taxi
            2 - admin
    */
    use Notifiable;

    protected $fillable = [
        'phone', 'name', 'email', 'password', 'surname', 'last_login', 'taxi_balance',
        'type', 'balance', 'car_info', 'car_number', 'additional_phone', 'firebase_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'additional_info' => 'array'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function ordersTaxi()
    {
        return $this->hasMany(OrderTaxi::class, 'user_id', 'id');
    }

    public function userFavorites()
    {
        return $this->hasMany(UserFavorite::class, 'user_id', 'id');
    }

    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function userCards()
    {
        return $this->hasMany(UserCard::class, 'user_id', 'id');
    }

    public function getImageAttribute($value)
    {
        return isset($value) ? secure_asset('uploads/users/' . $value) : null;
    }

    public static function addTaxiDriver($fields)
    {
        $user = new static;
        $user->fill($fields);
        if(isset($fields['password'])){
            $user->password = Hash::make($fields['password']);
        }
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        if(isset($fields['password'])){
            $this->password = Hash::make($fields['password']);
            $user_cards = UserCard::where('user_id', $this->id)->first();
            if($user_cards != null){
                foreach ($user_cards as $card) {
                    $card->delete();
                }
            }            
        }
        $this->save();
        return $this;
    }

    public function setFirebaseToken($token)
    {
        $this->firebase_token = $token;
        $this->save();
        return $this;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if ($this->image != null) {
            $image = explode("/", $this->image);

            unlink('uploads/users/' . $image[count($image)-1]);
        }
    }

    function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $path = public_path().'/uploads/users';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->removeImage();
        $filename = $this->id . "_random_" . rand(1, 1000000) . '.' . $image->extension();

        //$img = Image::make($image);
        //$img->save('uploads/users/' . $filename, 60);

        $image->move('uploads/users/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
