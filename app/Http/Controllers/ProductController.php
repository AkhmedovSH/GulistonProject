<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allProduct = Product::orderBy('id', 'DESC')->with('feedback')->get();

        return response()->json(
            [
                'result' => $allProduct
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

        dd($allProduct);
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
        $product = Product::with('feedback')->findOrFail($id);
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
            'product_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $feedback = ProductFeedback::add($request->all());
        
        return response()->json([
            'result' => $feedback
        ], 200);
    }
}
