<?php

namespace App\Http\Controllers\Taxi;

use App\OrderTaxi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\OrderTaxiCreateEvent;
use App\Events\OrderTaxiAcceptEvent;

class OrderTaxiController extends Controller
{
    public function createOrderTaxi(Request $request){
        
        $orderTaxiCreate = new OrderTaxi();
        $orderTaxiCreate = $orderTaxiCreate->add($request->all());

        $event = new OrderTaxiCreateEvent($orderTaxiCreate);
        broadcast($event);
    }

    public function acceptOrderTaxi(Request $request){

        $orderTaxiAccepted = OrderTaxi::where('id', $request->id)->first();
        $orderTaxiAccepted = $orderTaxiAccepted->statusAccepted();
        $orderTaxiAccepted = OrderTaxi::where('id', $request->id)->with('taxi_driver', 'user')->first();

        $event = new OrderTaxiAcceptEvent($orderTaxiAccepted);
        broadcast($event);
    }

    public function getDirection(Request $request){
        $response = $this->curlRequest($request);

        return response()->json(
            [
                'result' => $response
            ], 200);
    }

    public function getOrdersTaxi(){

        $allOrders = OrderTaxi::with(['user'])
        ->where('status', 0)
        ->orderBy('id','DESC')
        ->get();

        return response()->json(
            [
                'result' => $allOrders
            ], 200);
    }

    public function getTaxiDriverOrders(){

        $allTaxiDriverOrders = OrderTaxi::where('taxi_user_id', auth()->user()->id)
        ->orderBy('id','DESC')
        ->get();

        return response()->json(
            [
                'result' => $allTaxiDriverOrders
            ], 200);
    }

    public function curlRequest($request){
        $ch = curl_init("https://maps.googleapis.com/maps/api/directions/json?origin="
        . $request->fromLongitude . "," . $request->fromLatitude . "&destination="
        . $request->toLongitude . "," . $request->toLatitude .
        "&mode=driving&key=AIzaSyCJt4zV6nykDFFUXcVFqXH6EuhcX3e5kWQ");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $body = substr($body, $headerSize);
        $response = json_decode($body);
        curl_close($ch);

        return $response;
    }

}
