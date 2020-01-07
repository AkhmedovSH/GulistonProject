<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $allProduct = Product::orderBy('id', 'DESC')->paginate(1);
        return response()->json([
            'result' => $allProduct
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'price' => ['required'],
            'category_id' => ['required'],
            'company_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $product = Product::add($request->all());
        $product->addParameters($request->parameters);
        $product->uploadImage($request->file('image'));
        $product->uploadMultipleImages($request->file('images'));
        
        return response()->json([
            'result' => $product
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $product['parameters'] = json_decode($product['parameters']);
        return response()->json([
            'result' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required'],
            'category_id' => ['required'],
            'company_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $product = Product::find($request->id);
        $product->edit($request->all());
        $product->addParameters($request->parameters);
        $product->uploadImage($request->file('image'));
        $product->uploadMultipleImages($request->file('images'));

        return response()->json([
            'result' => $product
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Product::findOrFail($id)->remove();
            return response()->json([
                'success' => true
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Cannot delete'
                ], 400);
        }
    }
}
