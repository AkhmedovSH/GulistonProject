<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderTaxi extends Model
{
    const STATUS_CREATED  = 0;// ADD TO TAXI ORDER LIST
    const STATUS_ACCEPTED  = 1;// ONE TAXI ACCEPT ORDER

    protected $fillable = [
        'order_number', 'taxi_user_id', 'user_id', 'status',
        'price', 'order_accept_time', 'fromLongitude', 'fromLatitude',
        'toLongitude', 'toLatitude', 'startAddress', 'destinationAddress',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function taxi_driver()
    {
        return $this->belongsTo(User::class, 'taxi_user_id', 'id');
    }

    
    public static function add($fields)
    {
        $orderTaxi = new static;
        $orderTaxi->fill($fields);
        $orderTaxi->user_id = auth()->user()->id;
        $orderTaxi->save();

        return $orderTaxi;
    }
    

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
        return $this;
    }

    public function statusAccepted()
    {
        $this->status = $this::STATUS_ACCEPTED;
        $this->taxi_user_id = auth()->user()->id;
        $this->order_accept_time = Carbon::now();
        $this->order_number = 'TID' . auth()->user()->id . '_UID' . $this->user_id;
        $this->save();

        return $this;
    }
}
