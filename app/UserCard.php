<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $fillable = [
        'phone', 'card', 'expire', 'user_id', 'is_default'
    ];

    public static function add($fields)
    {
        $userCard = new static;
        $userCard->fill($fields);
        $userCard->save();

        return $userCard;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->status = true;
        $this->save();
    }
}
