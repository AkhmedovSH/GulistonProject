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

    public static function add($fields)
    {
        $category = new static;
        $category->title = $fields['title'];
        $category->parent_id = isset($fields['parent_id']) ? $fields['parent_id'] : null;
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
            unlink('uploads/categories/' . $this->image);
        }
    }

    function uploadImage($image)
    {
        
        if ($image == null) {
            return;
        }
        $this->removeImage();
        $filename = $this->id . '.' . $image->extension();
        $image->move('uploads/categories/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
