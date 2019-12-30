<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    const STATUS_CREATED  = 0;// ADD TO CART
    const STATUS_ORDERED  = 1;// CART ITEM PURCHASED
    const STATUS_ACCEPTED = 2;// PURCHASED ITEM ACCEPTED
    const STATUS_REJECT  = -1;// PURCHASED ITEM REJECTED

    protected $fillable = [
        'longitude', 'latitude', 'time', 'status', 'quantity', 'status_text', 'product_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function add($fields)
    {
        $order = new static;
        $order->fill($fields);
        $order->user_id = auth()->user()->id;
        $order->save();

        return $order;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
        return $this;
    }

    public static function deleteAll($orders)
    {
        foreach ($orders as $order) {
           $order->delete();
        }
    }

    public static function statusPurchased($orders, $address_id)
    {
        foreach ($orders as $order) {
           $order->status = $order::STATUS_ORDERED;
           $order->address_id = $address_id;
           $order->save();
        }
    }
}
