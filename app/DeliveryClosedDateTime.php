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

        if (!empty($fields['delivery_time_ids'])) {
            $deliveryClosedDateTime->delivery_time_ids = $fields['delivery_time_ids'];
        }else{
            $deliveryClosedDateTime->delivery_time_ids = NULL;
        }
        
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
