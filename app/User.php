<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{
    /* User types column type
            0 - simple user
            1 - admin
    */
    use Notifiable;

    protected $fillable = [
        'phone', 'name', 'email', 'password', 'surname', 'last_login', 'type'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function userFavorites()
    {
        return $this->hasMany(UserFavorite::class, 'user_id', 'id');
    }

    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function edit($fields)
    {
        $this->fill($fields);

        
        if(isset($fields['password'])){
            $this->password = Hash::make($fields['password']);
        }

        $this->save();
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
            unlink('uploads/users/' . $this->image);
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
        $filename = $this->id . '.' . $image->extension();
        $image->move('uploads/users/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
