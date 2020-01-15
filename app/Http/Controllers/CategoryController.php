<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResourceCollection;

class CategoryController extends Controller
{

    public function index()
    {
        $allCategory = Category::get();
        return response()->json(
            [
                'result' => $allCategory
            ], 200);
    }


    public function getCategoryProducts($category_id)
    {
        $allCategoryProducts = Product::where('category_id', $category_id)->get();
        return response()->json(
            [
                'result' => $allCategoryProducts
            ], 200);
    }
}
