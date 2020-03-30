<?php

namespace App;

use App\ProductAttribute;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class Product extends Model
{
    protected $fillable = [
        'title', 'price', 'available', 'quantity_type', 'hasAttributes', 
        'unit', 'increment', 'is_recommended','category_id',
        'famous', 'discount', 'keywords', 'company_id', 'bar_code', 'company_category_id'
    ];
    
    protected $casts = [
        'parameters' => 'array',
        'recommended_ids' => 'array',
        'is_recommended' => 'boolean',
        'hasAttributes' => 'boolean',
        'available' => 'boolean',
        'famous' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function attributes(){
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }
    
    public function getImageAttribute($value)
    {
        return isset($value) ? secure_asset('uploads/products/' . $value) : null;
    }

    public static function add($fields)
    {
        $product = new static;
        $product->fill($fields);
        $product->checkRecommendedProducts($fields);
        $product->save();
        
        return $product;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->checkRecommendedProducts($fields);
        $this->save();
        return $this;
    }


    public function checkRecommendedProducts($fields)
    {
        if(isset($fields['is_recommended'])){
            if($fields['is_recommended']  == 1 && $fields['recommended_ids'] != null && $fields['recommended_ids'] != "null"){
                $recomemdedArray = explode(",", $fields['recommended_ids']);
                if(isset($fields['recommended_ids']) && count($recomemdedArray) > 0){ //recommended product for a product ids
                    $this->recommended_ids = json_encode($recomemdedArray);
                }else{
                    $this->recommended_ids = NULL;
                }
            }else{
                $this->is_recommended = 0;
                $this->recommended_ids = NULL;
            }
        }
    }

    public function addParameters($parameters)
    {
        if ($parameters == null) { return; }
        $this->parameters = json_decode($parameters);
        $this->save();
    }

    public function addAttributes($attributes)
    {
        
        if ($attributes == null || empty($attributes) || $attributes == "[]") { return; }
        $this->hasAttributes = 1;
        $this->save();

        $decodedAttributes = json_decode($attributes, true);

        foreach($decodedAttributes as $key => $value){            
            $productAttribute = new ProductAttribute();
            $productAttribute->product_id = $this->id;
            $productAttribute->color = $decodedAttributes[$key]['color'];
            $productAttribute->size = $decodedAttributes[$key]['size'];
            $productAttribute->save();
        }
        
    }

    public function uploadMultipleAttributeImages($images){
        
        if ($images == null) { return; }

        $this->removeMultipleAttributeImages();

        $arrayItemsCount = count($images);
        $i = 0;
        $imgConcatenate = ";";
        foreach($images as $key => $image)
        {
            $filename = "productID_" . $this->id . "_randomAttribute_" . rand(1, 1000000). '.' . $image->extension();

            $img = Image::make($image);
            $img->save('uploads/products/' . $filename, 40);
            //$image->move('uploads/products/', $filename);
            if(++$i === $arrayItemsCount) {
                $imgConcatenate = $imgConcatenate . $filename;
            }else{
                $imgConcatenate = $imgConcatenate . $filename . ";";
            }
        }

        $this->images = $imgConcatenate;
        $this->save();
    }

    public function uploadMultipleImages($images){
        if ($images == null) { return; }
        
        $this->removeMultipleImages();
        $arrayItemsCount = count($images);
        $i = 0;
        $imgConcatenate = ";";
       
        foreach($images as $key => $image)
        {
            
            $filename = "productID_" . $this->id . "_random_" . rand(1, 1000000). '.' . $image->extension();

            $img = Image::make($image);
            $img->save('uploads/products/' . $filename, 40);

            //$image->move('uploads/products/', $filename);
            if(++$i === $arrayItemsCount) {
                $imgConcatenate = $imgConcatenate . $filename;
            }else{
                $imgConcatenate = $imgConcatenate . $filename . ";";
            }
        }
        
        $this->diff_images = $imgConcatenate;
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->removeMultipleAttributeImages();
        $this->removeMultipleImages();
        $this->removeFromUserFavorites();
        $this->delete();
    }

    public function removeFromUserFavorites()
    {
        $userFavorites = UserFavorite::where('product_id', $this->id)->get();
        foreach ($userFavorites as $favorite) {
            $favorite->delete();
        }
    }

    public function removeImage()
    {
        if ($this->image != null) {
            $image = explode("/", $this->image);

            if(file_exists('uploads/products/' . $image[count($image)-1]  )){
                unlink('uploads/products/' . $image[count($image)-1]);
            }
            
        }
    }

    function uploadImage($image)
    {
        if ($image == null) { return; }
        $this->removeImage();
        $filename = $this->id . "_random_" . rand(1, 1000000) . '.' . $image->extension();
        
        //$img = Image::make($image);
        //$img->save('uploads/products/' . $filename, 40);

        $image->move('uploads/products/', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function removeMultipleAttributeImages(){
        if ($this->images != null){
            $imagesArray = explode(";",$this->images);
            
            for($i = 1; $i < count($imagesArray); $i++){
                unlink('uploads/products/'. $imagesArray[$i]);
            }
        }
    }
    
    public function removeMultipleImages(){
        
        if ($this->diff_images != null){
            $imagesArray = explode(";",$this->diff_images);
            
            for($i = 1; $i < count($imagesArray); $i++){
                unlink('uploads/products/'. $imagesArray[$i]);
            }
        }
    }
}
