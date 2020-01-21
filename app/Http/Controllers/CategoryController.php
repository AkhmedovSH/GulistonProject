<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResourceCollection;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $allCategory = Category::get();

        $allCategory->map(function ($category) {
            $category->image = isset($category->image) ? secure_asset('uploads/categories/' . $category->image) : null;
            return $category;
        });

        return response()->json(
            [
                'result' => $allCategory
            ], 200);
    }

    public function getCategoryProducts($category_id)
    {
        $allCategoryProducts = Product::where('category_id', $category_id)->paginate(20);

        $allCategoryProducts->getCollection()->transform(function ($product) {
            $product->parameters = json_decode($product->parameters);
            $product->image = isset($product->image) ? secure_asset('uploads/products/' . $product->image) : null;
            $product->discountPrice = $product->discount != 0 ? $product->price - (($product->price / 100) * $product->discount) : null;
            return $product;
        });
        
        return response()->json(
            [
                'result' => $allCategoryProducts
            ], 200);
    }
}
