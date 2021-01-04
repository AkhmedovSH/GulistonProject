<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function categoryTopHome()
    {
        $mainCategories = Category::where('in_main_page', 1)->orderBy('in_main_page_position', 'ASC')->get();
       
        foreach ($mainCategories as $key => $value) {

            $mainCategories[$key]['data'] = 
            Product::where('category_id', $mainCategories[$key]['id'])->orderBy('id','DESC')->limit(20)->get();
        }
       
        return response()->json(
            [
                'result' => $mainCategories,
                    
            ], 200);
    }

    public function productFamous()
    {
        $famousProducts = Product::where('famous', 1)->orderBy('id', 'DESC')->paginate(20);

        return response()->json(
            [
                'result' => $famousProducts,
            ], 200);
    }

    public function productDiscount()
    {
        $discountProducts = Product::where('discount', '!=', 0)->orderBy('id', 'DESC')->paginate(20);

        return response()->json(
            [
                'result' => $discountProducts,
            ], 200);
    }

    public function productRandom()
    {
        $randomProducts = Product::inRandomOrder('id')->paginate(20);

        return response()->json(
            [
                'result' => $randomProducts,
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
				$arra = ['"'];
				$allProduct = DB::select( DB::raw("SELECT * FROM products WHERE REPLACE(LOWER(title), '$arra[0]', '') LIKE LOWER('%$request->title%')") );

				if(count($allProduct) == 0) {
					$allProduct =  Product::where('keywords', 'LIKE', "%$request->title%")->get();
				}
        //->orWhere('keywords', 'LIKE', "%$request->title%");

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
        $product = Product::where('id', $id)->with('attributes', 'category')->first();
        if($product->is_recommended == 1 && $product->recommended_ids != null){
            $recomemdedProducts = Product::whereIn('id', json_decode($product->recommended_ids))->get();
            $product['recommended'] = $recomemdedProducts;
            
        }
       
        return response()->json(
            [
                'result' => $product
            ], 200);
    }
}
