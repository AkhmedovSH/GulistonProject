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

        return response()->json(
            [
                'result' => $allProducts
            ], 200);
    }

    public function productTopHome()
    {
        $famousProducts = Product::where('famous', 1)->orderBy('id', 'DESC')->paginate(20);
        $discountProducts = Product::where('discount', '!=', 0)->orderBy('id', 'DESC')->paginate(20);
        $randomProducts = Product::inRandomOrder('id')->paginate(20);

        return response()->json(
            [
                'result' => 
                    [
                        $famousProducts,
                        $discountProducts,
                        $randomProducts,
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
        $product = Product::where('id', $id)->with('attributes')->first();
        return response()->json(
            [
                'result' => $product
            ], 200);
    }

}
