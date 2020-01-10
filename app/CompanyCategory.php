<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
            unlink('uploads/company_categories/' . $this->image);
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
        $filename = $this->id . '.' . $image->extension();
        $image->move('uploads/company_categories/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
