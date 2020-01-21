<?php

namespace App\Http\Controllers;

use App\Advertising;
use Illuminate\Http\Request;

class AdvertisingController extends Controller
{
    public function getAdvertising()
    {
        $allAdvertising = Advertising::orderBy('id', 'DESC')->take(5)->get();

        $allAdvertising->map(function ($advertising) {
            $advertising->image = isset($advertising->image) ? secure_asset('uploads/advertising/' . $advertising->image) : null;
            return $advertising;
        });

        return response()->json(
            [
                'result' => $allAdvertising
            ], 200);
    }
}
