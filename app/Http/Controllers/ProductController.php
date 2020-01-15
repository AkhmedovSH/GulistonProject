<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function productByCategories()
    {
        $mostFamous = Product::where('famous', 1)->orderBy(['id', 'DESC', 'created_at', 'DESC'])->limit(20);
        $mostSales = Product::where('sale', 1)->orderBy(['id', 'DESC', 'created_at', 'DESC'])->limit(20);

        return response()->json(
            [
                'result' => [
                    'mostFamous' => $mostFamous,
                    'mostSales' => $mostSales,
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
        $product = Product::with(['feedback'=>function($query){
            $query->with('user');
        }])->findOrFail($id);
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
