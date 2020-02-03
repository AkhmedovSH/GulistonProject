<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Product;
use App\UserFavorite;
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
        $allProduct = Product::orderBy('id', 'DESC')->with(['category'])->paginate(25);

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
            'price' => ['required'],
            'category_id' => ['required'],
            'company_id' => ['nullable'],
            'company_category_id' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $product = Product::add($request->all());
        $product->addAttributes($request->attribute);
        $product->addParameters($request->parameters);
        $product->uploadImage($request->file('image'));
        $product->uploadMultipleImages($request->file('attributeImages'));
        
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
        $product = Product::where('id', $id)->with('attributes')->first();
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
            'company_id' => ['nullable'],
            'company_category_id' => ['nullable'],
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
        $product->uploadMultipleImages($request->file('attributeImages'));

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
            $product = Product::findOrFail($id);

            $userFavorites = UserFavorite::where('product_id', $product->id)->get();
            $orders = Order::where('product_id', $product->id)->get();

            foreach($userFavorites as $favorite){
                $favorite->delete();
            }
            foreach($orders as $order){
                $order->delete();
            }

            $product->remove();
            return response()->json([
                'success' => true
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
                ], 400);
        }
    }

    public function productSearch(Request $request)
    {
        $query = Product::query();

        if($request->has('title')){
            $query->where('title', 'LIKE', "%$request->title%");
        }

        if($request->has('price')){
            $query->where('price', $request->price);
        }

        $products = $query->orderBy('id', 'DESC')->paginate(25);

        return response()->json(
            [
                'result' => $products
            ], 200);
    }
}
