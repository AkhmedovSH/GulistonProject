<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();

        $allProduct = $product->map(function ($product) {
            return [
                "id" => $product->id,
                "title" => $product->title,
                "description" => $product->description,
                "price" => $product->price,
                "image" => asset('uploads/products/' . $product->image),
                "images" => $product->images,
                "available" => $product->available,
                "favorite" => $product->favorite,
                "keywords" => $product->keywords,
                "company_id" => $product->company_id,
                "category_id" => $product->category_id,
                "created_at" => $product->created_at,
            ];
        });

        return response()->json($allProduct, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product['image'] = asset('uploads/products/' . $product->image);
        return response()->json($product, 200);
    }
}
