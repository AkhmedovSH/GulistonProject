<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryClosedDateTime extends Model
{
    protected $fillable = [
        'date', 'delivery_time_ids', 
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $casts = [
        'delivery_time_ids', 'array',
    ];

    public static function add($fields)
    {
        $deliveryClosedDateTime = new static;
        $deliveryClosedDateTime->fill($fields);
        $deliveryClosedDateTime->save();
        return $deliveryClosedDateTime;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
        return $this;
    }
}
