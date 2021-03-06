<?php

namespace App\Http\Controllers;

use App\Advertising;
use Illuminate\Http\Request;

class AdvertisingController extends Controller
{
    public function getAdvertising()
    {
        $allAdvertising = Advertising::orderBy('id', 'DESC')->take(5)->get();

        return response()->json(
            [
                'result' => $allAdvertising
            ], 200);
    }
}
