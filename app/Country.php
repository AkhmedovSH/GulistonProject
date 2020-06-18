<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'title'
    ];

    public $timestamps = false;

    public static function add($fields)
    {
        $data = new static;
        $data->fill($fields);
        $data->save();

        return $data;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }
}
