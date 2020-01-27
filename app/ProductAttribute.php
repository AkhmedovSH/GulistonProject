<?php

namespace App;

use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $fillable = ['product_id', 'color', 'size', 'image'];

    function uploadImage($image)
    {
        if ($image == null) { return; }

        $this->removeImage();
        $filename = $this->id . '.' . $image->extension();
        
        $img = Image::make($image);
        $img->save('uploads/products/' . $filename, 60);

        $this->image = $filename;
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

            unlink('uploads/products/' . $image[count($image)-1]);
        }
    }
}
