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
        'longitude', 'latitude', 'time_id', 'status', 'quantity', 'status_text',
         'product_id', 'user_id', 'color', 'size', 'image'
    ];

    public function getImageAttribute($value)
    {
        return isset($value) ? secure_asset('uploads/products/' . $value) : null;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function userAddress()
    {
        return $this->hasOne(UserAddress::class, 'id', 'user_id');
    }

    public static function add($fields)
    {
        $order = new static;
        $order->fill($fields);
        $order->user_id = auth()->user()->id;
        if($fields['image'] && $fields['image'] != null){
            $image = explode("/", $fields['image']);
            $order->image = $image[count($image)-1];
        }
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
           $order->order_number = $order->id . $order->product_id;
           $order->save();
        }
    }

    public function statusAccepted($orders, $address_id)
    {
        $this->status = $this::STATUS_ORDERED;
        $this->save();
    }

    public function statusRejected()
    {
        $this->status = $this::STATUS_REJECT;
        $this->save();
    }
}
