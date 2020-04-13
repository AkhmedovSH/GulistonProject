<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Company extends Model
{
    protected $fillable = [
        'title', 'description', 'address', 'phone'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(CompanyCategory::class);
    }

    public function getImageAttribute($value)
    {
        return isset($value) ? secure_asset('uploads/companies/' . $value) : null;
    }

    public static function add($fields)
    {
        $company = new static;
        $company->fill($fields);
        $company->save();

        return $company;
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

            unlink('uploads/companies/' . $image[count($image)-1]);
        }
    }

    function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $path = public_path().'/uploads/companies';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->removeImage();
        $filename = $this->id . "_random_" . rand(1, 1000000) . '.' . $image->extension();

        //$img = Image::make($image);
        //$img->save('uploads/companies/' . $filename, 60);

        $image->move('uploads/companies/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
