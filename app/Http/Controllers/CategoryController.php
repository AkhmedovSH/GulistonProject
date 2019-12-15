<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $category = Category::all();
        return response()->json([$category], 200);
    }

    public function show(Category $category)
    {
        return response()->json([$category], 200);
    }

}
