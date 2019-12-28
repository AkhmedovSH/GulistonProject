<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeedback extends Model
{
    protected $fillable = [
        'description', 'product_id', 'user_id'
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
        $feedback = new static;
        $feedback->fill($fields);
        $feedback->save();

        return $feedback;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }
}
