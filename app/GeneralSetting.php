<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'title', 'key', 'value', 
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public static function add($fields)
    {
        $generalSetting = new static;
        $generalSetting->fill($fields);
        $generalSetting->save();
        return $generalSetting;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
        return $this;
    }
}
