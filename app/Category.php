<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'title', 'parent_id'
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
        $category->title = $fields['title'];
        $category->parent_id = isset($fields['parent_id']) ? $fields['parent_id'] : null;
        if($fields['position'] != 'null'){
            $category->position = $fields['position'];
        }
        $category->save();

        return $category;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        if($fields['position'] != 'null'){
            $category->position = $fields['position'];
        }
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
            unlink('uploads/categories/' . $this->image);
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
        $filename = $this->id . '.' . $image->extension();
        $image->move('uploads/categories/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
