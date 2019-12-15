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
        $this->removeImage();
        $filename = $this->id . '.' . $image->extension();

        $image->move('uploads/companies/', $filename);
        $this->image = $filename;
        $this->save();
    }
}
