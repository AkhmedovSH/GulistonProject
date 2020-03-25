<?php

namespace App;

use Intervention\Image\Facades\Image as Image;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title', 'parent_id', 'position', 'in_main_page', 'in_main_page_position'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function getImageAttribute($value)
    {
        return isset($value) ? secure_asset('uploads/categories/' . $value) : null;
    }

    public static function add($fields)
    {
        $category = new static;
        $category->fill($fields);
        $category->save();

        return $category;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if ($this->image != null) {
            $image = explode("/", $this->image);

            unlink('uploads/categories/' . $image[count($image)-1]);
        }
    }

    function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $path = public_path().'/uploads/categories';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
       
        $this->removeImage();
        $filename = $this->id . "_random_" . rand(1, 1000000) . '.' . $image->extension();
        
        $img = Image::make($image);
        $img->save('uploads/categories/' . $filename, 60);

        //$image->move('uploads/categories/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
