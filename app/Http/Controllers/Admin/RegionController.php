<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Region;
use App\Street;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Region::orderBy('id', 'DESC')->with('country')->get();

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
        $data = Region::add($request->all());
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
        $data = Region::find($id);
        return response()->json([
                'result' => $data
            ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $data = Region::find($request->id);
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
        $region = Region::where('id', $id)->first();
        $cities = City::where('region_id', $region->id)->get();
        if($cities != null) {
            foreach ($cities as $city) {
                $streets = Street::where('city_id', $city->id)->get();
                foreach ($streets as $street) {
                    $street->delete();
                }
                $city->delete();
            }
        }
        $region->delete();

        return response()->json([
            'result' => true
        ], 200);
    }
}
