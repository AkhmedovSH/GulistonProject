<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'title', 'country_id'
    ];

    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
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
