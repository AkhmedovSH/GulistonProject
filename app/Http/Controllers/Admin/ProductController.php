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
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $product = Product::add($request->all());
        $product->uploadImage($request->file('image'));
        $product->uploadMultipleImages($request->file('images'));
        
        return $product;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        return response()->json([$product], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'parent_id' => 'nullable',
            'image' => 'nullable'
        ]);
       
        $product = Product::find($id);
        $product->edit($request->all());
        $product->uploadImage($request->file('image'));
        
        return $product;
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
            Product::find($id)->remove();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Cannot delete'], 200);
        }

        return response()->json(['success' => 'Deleted'], 200);
    }
}
