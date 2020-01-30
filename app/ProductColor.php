<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $fillable = [
        'hex'
    ];

    public static function add($fields)
    {
        $productColor = new static;
        $productColor->fill($fields);
        $productColor->save();

        return $productColor;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }
}
