<?php

namespace App\Http\Controllers;

use App\OrderTaxi;
use Carbon\Carbon;
use App\DeliveryTime;
use App\GeneralSetting;
use Illuminate\Http\Request;
use App\DeliveryClosedDateTime;
use Illuminate\Support\Facades\Redis;

class MainController extends Controller
{
    public function redis_test(Request $request){

        $taxiOrders = OrderTaxi::where('status', 0)->get();
        foreach($taxiOrders as $order){
            $totalDuration = $order->created_at->diffInMinutes(Carbon::now());
            dd($totalDuration, $order->created_at, Carbon::now());
            if($totalDuration > 15){
                $order->delete();
            }
        }

        /* try{
            $redis=Redis::connect('127.0.0.1',3306);
            return response('redis working');
        }catch(\Predis\Connection\ConnectionException $e){
            return response('error connection redis');
        } */
    }

    public function getGeneralSetting()
    {
        $settings = GeneralSetting::all();
        
        return response()->json([
            'result' => $settings
        ], 200);
    }

    
    public function deliveryTable()
    {
        $setting = GeneralSetting::where('key', 'max_days')->first();

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
}
