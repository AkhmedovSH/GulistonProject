<?php

namespace App\Http\Controllers;

use App\Advertising;
use Illuminate\Http\Request;

class AdvertisingController extends Controller
{
    public function index()
    {
        $allAdvertising = Advertising::orderBy('id', 'DESC')->get();
        return response()->json(
            [
                'result' => $allAdvertising
            ], 200);
    }
}