<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'available', 'deliver', 'keywords', 'company_id', 'category_id', 'sale', 'discount'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function feedback()
    {
        return $this->hasMany(ProductFeedback::class, 'product_id', 'id');
    }


    public static function add($fields)
    {
        
        $product = new static;
        $product->fill($fields);
        $product->save();

        return $product;
    }

    public function addParameters($parameters)
    {
        if ($parameters == null) { return; }
        $this->parameters = $parameters;
        $this->save();
    }

    public function edit($fields)
    {
        $this->fill($fields);
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

    public function remove()
    {
        $this->removeImage();
        $this->removeMultipleImages();
        $this->delete();
    }

    public function removeImage()
    {
        if ($this->image != null) {
            unlink('uploads/products/' . $this->image);
        }
    }

    function uploadImage($image)
    {
        if ($image == null) {
            return;
        }
        $this->removeImage();
        $filename = $this->id . '.' . $image->extension();
        
        $image->move('uploads/products/', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function uploadMultipleImages($images){
        if ($images == null) { return; }

        
        $arrayItemsCount = count($images);
        $i = 0;
        $imgConcatenate = ";";
        foreach($images as $key => $image)
        {
            $filename = "productID_" . $this->id . "_random_" . rand(1, 1000000). '.' . $image->extension();
            $image->move('uploads/products/', $filename);
            if(++$i === $arrayItemsCount) {
                $imgConcatenate = $imgConcatenate . $filename;
            }else{
                $imgConcatenate = $imgConcatenate . $filename . ";";
            }
        }

        $this->images = $imgConcatenate;
        $this->save();
    }

    public function removeMultipleImages($images){
        if ($this->images != null){
            $imagesArray = explode(";",$this->images);
            
            for($i = 1; $i < count($imagesArray); $i++){
                unlink('uploads/products/'. $imagesArray[$i]);
            }
        }
    }
}
