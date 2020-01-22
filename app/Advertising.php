<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Advertising extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    public function getImageAttribute($value)
    {
        return isset($value) ? secure_asset('uploads/advertising/' . $value) : null;
    }
    
    public static function add($fields)
    {
        $advertising = new static;
        $advertising->fill($fields);
        $advertising->save();

        return $advertising;
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

            unlink('uploads/advertising/' . $image[count($image)-1]);
        }
    }

    function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $path = public_path().'/uploads/advertising';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
       
        $this->removeImage();
        $filename = $this->id . '.' . $image->extension();

        $img = Image::make($image);
        $img->save('uploads/advertising/' . $filename, 60);

        //$image->move('uploads/advertising/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
