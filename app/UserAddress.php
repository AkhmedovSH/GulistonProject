<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'street', 'state', 'city', 'postal_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function add($fields)
    {
        $user_address = new static;
        $user_address->fill($fields);
        $user_address->user_id = auth()->user()->id;
        $user_address->save();

        return $user_address;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();

        return $this;
    }
}
