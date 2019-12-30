<?php

namespace App\Http\Controllers;

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
}
