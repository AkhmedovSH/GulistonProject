<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeedback extends Model
{
    protected $fillable = [
        'title', 'description', 'product_id'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
