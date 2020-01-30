<?php

namespace App;

use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $hidden = ['id', 'created_at', 'updated_at', 'product_id'];

    protected $fillable = ['product_id', 'color', 'size'];

}
