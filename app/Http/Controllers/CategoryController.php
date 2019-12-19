<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResourceCollection;

class CategoryController extends Controller
{

    public function index()
    {
        $allCategory = Category::all();

       /*  $category->getCollection()->transform(function ($category) {
            $category->image = isset($category->image) ? asset('uploads/categories/' . $category->image) : null;
            return $category;
        }); */

        /* $allCategory = $category->map(function ($category) {
            return [
                "id" => $category->id,
                "title" => $category->title,
                "image" => isset($category->image) ? asset('uploads/categories/' . $category->image) : null,
                "parent_id" => $category->parent_id,
                "created_at" => $category->created_at,
            ];
        }); */

        return response()->json(
            [
                'result' => $allCategory
            ], 200);
    }

    public function show(Category $category)
    {
        return response()->json(
            [
                'result' => $category
            ], 200);
    }

}
