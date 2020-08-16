<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'is_default', 'street', 'name', 'phone', 'postal_code', 'longitude', 'latitude', 'room_number', 'ref_point',
        'country_id', 'region_id', 'city_id', 'street_id'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country_r()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function region_r()
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }

    public function city_r()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function street_r()
    {
        return $this->hasOne(Street::class, 'id', 'street_id');
    }

    public static function add($fields)
    {
        $defaultAddress = self::where('user_id', auth()->user()->id)->first();

        $userAddress = new static;
        $userAddress->fill($fields);
        $userAddress->user_id = auth()->user()->id;
        $userAddress->is_default = $defaultAddress == null ? 1 : 0;
        $userAddress->save();

        return $userAddress;
    }

    public function edit($fields)
    {

        $this->fill($fields);
        $this->save();

        return $this;
    }
}
