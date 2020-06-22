<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    protected $fillable = [
        'title', 'city_id'
    ];

    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

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
