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
        $allProduct = Product::orderBy('id', 'DESC')->get();

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
