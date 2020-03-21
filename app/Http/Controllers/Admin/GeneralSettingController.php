<?php

namespace App\Http\Controllers\Admin;

use App\GeneralSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = GeneralSetting::get();
        
        return response()->json([
            'result' => $setting
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
        $setting = GeneralSetting::where('id', $id)->first();
        return response()->json([
            'result' => $setting
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
        $setting = GeneralSetting::where('id', $request->id)->first();
        $setting->edit($request->all());
        
        return response()->json([
            'result' => $setting
        ], 200);
    }

}
