<?php

namespace App\Http\Controllers\Admin;

use App\ProductColor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductAttributeController extends Controller
{
    public function getColors()
    {
        $allColors = ProductColor::get();

        return response()->json([
            'result' => $allColors
        ], 200);
    }

    public function geSizes()
    {
        $allSizes = ProductSize::get();

        return response()->json([
            'result' => $allSizes
        ], 200);
    }
}
