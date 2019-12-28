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
        $allProduct = Product::all();

        /* $allProduct = $product->map(function ($product) {
            return [
                "result" => [
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
                ]
            ];
        }); */

        return response()->json(
            [
                'result' => $allProduct
            ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product['parameters'] = json_decode($product['parameters']);
        return response()->json(
            [
                'result' => $product
            ], 200);
    }


    public function addFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required', 'max:500'],
            'product_id' => ['required'],
            'user_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $order = Order::add($request->all());
        
        return response()->json([
            'result' => $order
        ], 200);
    }
}
