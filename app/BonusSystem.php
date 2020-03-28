<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusSystem extends Model
{
    protected $fillable = [
        'price', 'price_amount', 'price_percentage'
    ];

    public static function add($fields)
    {
        $bonus = new static;
        $bonus->fill($fields);
        $bonus->save();

        return $bonus;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }
}
