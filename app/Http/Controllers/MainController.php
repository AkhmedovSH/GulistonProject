<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\DeliveryTime;
use App\GeneralSetting;
use Illuminate\Http\Request;
use App\DeliveryClosedDateTime;
use Illuminate\Support\Facades\Redis;

class MainController extends Controller
{
    public function redis_test(Request $request){
        try{
            $redis=Redis::connect('127.0.0.1',3306);
            return response('redis working');
        }catch(\Predis\Connection\ConnectionException $e){
            return response('error connection redis');
        }
    }
    
    public function deliveryTable()
    {
        $setting = GeneralSetting::where('key', 'maxDays')->first();

        $adminDays = (int)$setting->value;

        $timesFromDB = DeliveryTime::all()->toArray();
        $deliveryClosedDays = DeliveryClosedDateTime::all()->toArray();

        for ($i = 0; $i <= $adminDays; $i++) {
            $dateTimeTable[$i] = [];
            $times = $timesFromDB;

            $dateTimeTable[$i]['date'] = Carbon::now()->addDays($i)->format('Y-m-d');
            $dateTimeTable[$i]['monthName'] = Carbon::now()->addDays($i)->format('F');
            $dateTimeTable[$i]['fullDate'] = Carbon::now()->addDays($i);
            $dateTimeTable[$i]['is_active'] = true;

            foreach ($deliveryClosedDays as $key => $val) {
                if($deliveryClosedDays[$key]['date'] == $dateTimeTable[$i]['date']){

                    if($deliveryClosedDays[$key]['delivery_time_ids'] == null){
                        $dateTimeTable[$i]['is_active'] = false;
                    }
                    

                    $delivery_time_ids = json_decode($deliveryClosedDays[$key]['delivery_time_ids']);
                    if($delivery_time_ids != null)
                        foreach ($delivery_time_ids as $key2 => $val2) {
                            foreach ($times as $key3 => $val3) {
                                if($times[$key3]['id'] == $delivery_time_ids[$key2]){
                                    $times[$key3]['is_active'] = false;
                                }
                            }
                        }
                }
            }

            $dateTimeTable[$i]['times'] = $times; 
        }
        
        return response()->json(
            [
                'result' => $dateTimeTable
            ], 200);
    }

    public function getDirections($data){
        $response = $this->curlRequest($data);

        return response()->json(
            [
                'result' => $response
            ], 200);
    }

    public function curlRequest($payload){
        $ch = curl_init("https://maps.googleapis.com/maps/api/directions/json?origin=41.32322099374842,69.2597983404994&destination=41.32056781558941,69.26212817430496&mode=driving&key=AIzaSyCJt4zV6nykDFFUXcVFqXH6EuhcX3e5kWQ");
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
