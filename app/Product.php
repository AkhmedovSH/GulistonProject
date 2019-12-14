<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'available', 'favorite', 'keywords', 'company_id', 'category_id'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public static function add($fields)
    {
        $product = new static;
        $product->fill($fields);
        $product->save();

        return $product;
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
            Storage::delete('uploads/posts/' . $this->image);
        }
    }

    function uploadImage($image)
    {
        if ($image == null) {
            return;
        }
        $this->removeImage();
        $filename = $this->id . '.' . $image->extension();

        $image->storeAs('uploads/products/', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }
    public function setCompany($id)
    {
        if ($id == null) {
            return;
        }
        $this->company_id = $id;
        $this->save();
    }
}
