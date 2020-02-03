<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function generalSettings()
    {
        $days = Settings::orderBy('id', 'DESC')->paginate(20);

        return response()->json(
            [
                'result' => [
                    'maxDays' => $days
                ]
            ], 200);
    }
}
