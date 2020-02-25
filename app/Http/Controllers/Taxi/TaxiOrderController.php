<?php

namespace App\Http\Controllers\Taxi;

use App\Events\TaxiOrderEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxiOrderController extends Controller
{
    public function index($name){
        $event = new TaxiOrderEvent($name);
        event($event);
    }

}
