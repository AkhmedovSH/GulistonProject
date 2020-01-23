<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOptions extends Model
{
    protected $fillable = ['product_id', 'color_id', 'size_id'];

    protected $hidden = ['color_id', 'product_id', 'size_id', 'id'];

    public function color(){
        return $this->belongsTo(ProductColor::class);
    }

    public function size(){
        return $this->belongsTo(ProductSize::class);
    }
}
