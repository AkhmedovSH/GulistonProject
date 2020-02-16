<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryTime extends Model
{
    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
