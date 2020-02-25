<?php

namespace App\Http\Controllers\Taxi;

use App\Events\TaxiOrderEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxiOrderController extends Controller
{
    public function index(Request $request){
        TaxiOrderEvent::dispatch($request->user);
    }
}
