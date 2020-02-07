<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class CompanyCategory extends Model
{
    protected $fillable = [
        'title', 'company_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'company_category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function getImageAttribute($value)
    {
        return isset($value) ? secure_asset('uploads/company_categories/' . $value) : null;
    }

    public static function add($fields)
    {
        $category = new static;
        $category->fill($fields);
        if(isset($fields['position']) && $fields['position'] != 'null'){
            $category->position = $fields['position'];
        }
        $category->save();

        return $category;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        if(isset($fields['position']) && $fields['position'] != 'null'){
            $this->position = $fields['position'];
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
            $image = explode("/", $this->image);

            unlink('uploads/company_categories/' . $image[count($image)-1]);
        }
    }
    function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $path = public_path().'/uploads/company_categories';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->removeImage();
        $filename = $this->id . "_random_" . rand(1, 1000000) . '.' . $image->extension();
        
        $img = Image::make($image);
        $img->save('uploads/company_categories/' . $filename, 60);

        //$image->move('uploads/company_categories/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
