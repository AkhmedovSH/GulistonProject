<?php

namespace App\Http\Controllers\Taxi;

use App\OrderTaxi;
use Illuminate\Http\Request;
use App\Events\TaxiOrderEvent;
use App\Http\Controllers\Controller;

class TaxiOrderController extends Controller
{
    public function index($name){
        $event = new TaxiOrderEvent($name);
        event($event);
    }

    public function createOrderTaxi(Request $request){
        
        $orderTaxi = new OrderTaxi();
        $orderTaxi = $orderTaxi->add($request->all());

        $event = new TaxiOrderEvent($name);
        $event = json_encode(['result' => $event]);
        event($event);
    }

    public function acceptOrderTaxi(Request $request){

        $orderTaxi = OrderTaxi::where('id', $request->id)->first();
        $orderTaxi = $orderTaxi->statusAccepted();

        $event = new TaxiOrderEvent();
        $event = json_encode(['result' => $event]);
        event($event);
    }

}
