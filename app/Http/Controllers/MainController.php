<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\DeliveryTime;
use App\GeneralSetting;
use Illuminate\Http\Request;
use App\DeliveryClosedDateTime;

class MainController extends Controller
{
    public function deliveryTable()
    {
        $setting = GeneralSetting::where('key', 'maxDays')->first();

        $adminDays = (int)$setting->value;

        $timesFromDB = DeliveryTime::all()->toArray();
        $deliveryClosedDays = DeliveryClosedDateTime::all()->toArray();

        for ($i = 0; $i <= $adminDays; $i++) {
            $dateTimeTable[$i] = [];
            $times = $timesFromDB;

            $dateTimeTable[$i]['date'] = Carbon::now()->addDays($i)->format('d-m-Y');
            $dateTimeTable[$i]['monthName'] = Carbon::now()->addDays($i)->format('F');
            $dateTimeTable[$i]['fullDate'] = Carbon::now()->addDays($i);
            $dateTimeTable[$i]['is_active'] = true;

            foreach ($deliveryClosedDays as $key => $val) {
                if($deliveryClosedDays[$key]['date'] == $dateTimeTable[$i]['date']){
                    $dateTimeTable[$i]['is_active'] = false;

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
