<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResourceCollection;

class CategoryController extends Controller
{

    public function index()
    {
        $category = Category::all();

        $allCategory = $category->map(function ($category) {
            return [
                "result" => [
                    "id" => $category->id,
                    "title" => $category->title,
                    "image" => asset('uploads/categories/' . $category->image),
                    "parent_id" => $category->parent_id,
                    "created_at" => $category->created_at,
                ]
            ];
        });
 
        return response()->json($allCategory, 200);

    }

    public function show(Category $category)
    {
        return response()->json(
            [
                "result" => [
                    $category
                ]
            ], 200);
    }

}
