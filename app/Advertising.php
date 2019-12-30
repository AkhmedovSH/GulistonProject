<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    protected $fillable = [
        'title', 'company_id', 'product_id'
    ];
    
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
            unlink('uploads/advertising/' . $this->image);
        }
    }

    function uploadImage($image)
    {
        
        if ($image == null) {
            return;
        }
       
        $this->removeImage();
        
        $filename = $this->id . '.' . $image->extension();
       
        $image->move('uploads/advertising/', $filename);
       
        $this->image = $filename;
       
        $this->save();
        
    }
}
