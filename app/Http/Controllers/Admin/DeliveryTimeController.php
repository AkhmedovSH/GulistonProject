<?php

namespace App\Http\Controllers\Admin;

use App\DeliveryTime;
use App\GeneralSetting;
use Illuminate\Http\Request;
use App\DeliveryClosedDateTime;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DeliveryTimeController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryClosedTimes = DeliveryClosedDateTime::orderBy('id', 'DESC')->get();
        
        return response()->json([
            'result' => $deliveryClosedTimes
        ], 200);
    }

    public function deliveryTimes()
    {
        $deliveryTimes = DeliveryTime::orderBy('id', 'DESC')->get();
        
        return response()->json([
            'result' => $deliveryTimes
        ], 200);
    }

    public function setDeliveryMonthDays(Request $request)
    {
        $setting = GeneralSetting::where('key', 'max_days')->first();
        $setting->edit($request->day);

        return response()->json([
            'result' => $setting
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $deliveryClosedTime = DeliveryClosedDateTime::add($request->all());
        
        return response()->json([
            'result' => $deliveryClosedTime
        ], 200);
    }

   /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deliveryClosedTime = DeliveryClosedDateTime::find($id);
        return response()->json([
            'result' => $deliveryClosedTime
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $deliveryClosedTime = DeliveryClosedDateTime::find($request->id);
        $deliveryClosedTime->edit($request->all());
        
        return response()->json([
            'result' => $deliveryClosedTime
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DeliveryClosedDateTime::find($id)->delete();
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => $th->getMessage()
                ], 400);
        }

        return response()->json([
            'success' => true
            ], 200);
    }
}
