<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $hidden = ['id'];

    protected $fillable = ['product_id', 'color', 'size', 'image'];
}
