<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function productAll()
    {
        $allProducts = Product::orderBy('id', 'DESC')
        ->paginate(20);

        $allProducts->getCollection()->transform(function ($product) {
            $product->parameters = json_decode($product->parameters);
            $product->image = isset($product->image) ? secure_asset('uploads/products/' . $product->image) : null;
            $product->discountPrice = $product->discount != 0 ? $product->price - (($product->price / 100) * $product->discount) : null;
            return $product;
        });


        return response()->json(
            [
                'result' => $allProducts
            ], 200);
    }

    public function productTopHome()
    {
        $mostFamous = Product::where('famous', 1)->orderBy('id', 'DESC')->paginate(20);

        $mostFamous->getCollection()->transform(function ($product) {
            $product->parameters = json_decode($product->parameters);
            $product->image = isset($product->image) ? secure_asset('uploads/products/' . $product->image) : null;
            $product->discountPrice = $product->discount != 0 ? $product->price - (($product->price / 100) * $product->discount) : null;
            return $product;
        });

        $mostSaled = Product::where('sale', 1)->orderBy('id', 'DESC')->paginate(20);

        $mostSaled->getCollection()->transform(function ($product) {
            $product->parameters = json_decode($product->parameters);
            $product->image = isset($product->image) ? secure_asset('uploads/products/' . $product->image) : null;
            $product->discountPrice = $product->discount != 0 ? $product->price - (($product->price / 100) * $product->discount) : null;
            return $product;
        });

        return response()->json(
            [
                'result' => 
                    [
                        $mostFamous,
                        $mostSaled,
                    ]
            ], 200);
    }


    public function productSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'min:2'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $allProduct =  Product::where('title', 'LIKE', "%$request->title%")
        ->orWhere('keywords', 'LIKE', "%$request->title%")->get();

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
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product['parameters'] = json_decode($product['parameters']);
        $product['image'] = isset($product['image']) ? secure_asset('uploads/products/' . $product['image']) : null;
        return response()->json(
            [
                'result' => $product
            ], 200);
    }

}
