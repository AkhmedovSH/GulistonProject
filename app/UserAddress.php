<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'is_default', 'name', 'phone', 'street', 'state',
        'city', 'postal_code', 'longitude', 'latitude', 'room_number', 'ref_point'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
