<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Product;
use App\UserFavorite;
use App\Scopes\ProductScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if($request->get('title') != "null"){       
            $query->where('title', 'LIKE', "%$request->title%");
        }

        if(json_decode($request->get('id')) != null){
            $query->where('id', $request->id);
        }

        if(json_decode($request->get('available')) != null){
            $query->where('available', $request->available);
        }

        if(json_decode($request->get('bar_code')) != null){
            $query->where('bar_code', $request->bar_code);
        }

        if(json_decode($request->get('category_id')) != null){
            $query->where('category_id', $request->category_id);
        }

        if(json_decode($request->get('price')) != null){
            $query->where('price', $request->price);
        }

        $products = $query->withoutGlobalScope(ProductScope::class)->orderBy('id', 'DESC')->with(['category'])->paginate(100);

        return response()->json(
            [
                'result' => $products
            ], 200);
    }

    public function productSearch(Request $request)
    {
        $q = $request->value;
        $products = Product::
        withoutGlobalScope(ProductScope::class)
        ->where(function($query) use ($q) {
            $query->where('id', 'LIKE', '%'.$q.'%')
                ->orWhere('title', 'LIKE', '%'.$q.'%');
        })->get();

        return response()->json(
            [
                'result' => $products
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
        $product->uploadMultipleImages($request->file('diff_images'));
        $product->uploadMultipleAttributeImages($request->file('attributeImages'));
        
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
        
        $product = Product::withoutGlobalScope(ProductScope::class)->where('id', $id)->with('attributes')->first();
        if($product->is_recommended == 1 && $product->recommended_ids != null){
            $recomemdedProducts = Product::whereIn('id', json_decode($product->recommended_ids))->get();
            $product['recommended'] = $recomemdedProducts;
        }
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
        $product = Product::withoutGlobalScope(ProductScope::class)->where('id', $request->id)->first();
        $product->edit($request->all());
        $product->addParameters($request->parameters);
        $product->uploadImage($request->file('image'));
        $product->uploadMultipleAttributeImages($request->file('attributeImages'));

        return response()->json([
            'result' => $product
            ], 200);
    }


    public function availableBool(Request $request)
    {
        $product = Product::withoutGlobalScope(ProductScope::class)->where('id', $request->id)->first();
        if($product->available == 1){
            $product->available = 0;
            $product->save();
        }else{
            $product->available = 1;
            $product->save();
        }

        $orders = Order::where('product_id', $product->id)->where('status', 0)->get();
        if($orders != null){
            foreach ($orders as $key => $order) {
                $order->delete();
            }
        }
        
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
            $product = Product::withoutGlobalScope(ProductScope::class)->findOrFail($id);

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
}
