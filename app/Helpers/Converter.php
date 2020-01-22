<?php

namespace App\Helpers;

class Converter
{
	public  function ArrayContainsObjects($data)
	{
        $array = [];
        foreach($data as $key => $value) {
            array_push($array, 
            [
                'title' => $value,
                'id' => $key,
            ]);
        }
        return $array;
    }

    public  function ProductsTransform($allProducts)
	{
        $allProducts->getCollection()->transform(function ($product) {
            $product->parameters = json_decode($product->parameters);
            $product->image = isset($product->image) ? secure_asset('uploads/products/' . $product->image) : null;
            $product->discountPrice = $product->discount != 0 ? $product->price - (($product->price / 100) * $product->discount) : null;
            return $product;
        });

        return $allProducts;
    }

    public  function ProductTransform($allProducts)
	{
        $array = [];
        foreach($data as $key => $value) {
            array_push($array, 
            [
                'title' => $value,
                'id' => $key,
            ]);
        }
        return $array;
    }
}