<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'key', 'value', 
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

    public function edit($value)
    {
        $this->value = $value;
        $this->save();
        return $this;
    }
}
