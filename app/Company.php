<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'title', 'description'
    ];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(CompanyCategory::class);
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
            unlink('uploads/companies/' . $this->image);
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
        $filename = $this->id . '.' . $image->extension();
        $image->move('uploads/companies/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
