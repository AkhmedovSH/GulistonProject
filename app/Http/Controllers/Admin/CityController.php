<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Street;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = City::orderBy('id', 'DESC')->with('region')->get();

        return response()->json(
            [
                'result' => $data
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
        $data = City::add($request->all());
        return response()->json(
            [
                'result' => $data
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
        $data = City::find($id);
        return response()->json([
                'result' => $data
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
        
        $data = City::find($request->id);
       
        $data->edit($request->all());

        return response()->json([
            'result' => $data
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
        $city = City::where('id', $id)->first();
        $streets = Street::where('city_id', $city->id)->get();
        if($streets != null) {
            foreach ($streets as $street) {
                $street->delete();
            }
        }
        $city->delete();

        return response()->json([
            'result' => true
        ], 200);
    }
}
