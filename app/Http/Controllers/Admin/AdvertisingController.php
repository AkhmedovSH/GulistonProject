<?php

namespace App\Http\Controllers\Admin;

use App\Advertising;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdvertisingController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allAdvertising = Advertising::orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $allAdvertising
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'image' => ['nullable'],
        ]);
       
        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $advertising = Advertising::add($request->all());
        $advertising->uploadImage($request->file('image'));
        return response()->json(
            [
                'result' => $advertising
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
        $advertising = Advertising::find($id);
        return response()->json([
                'result' => $advertising
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
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'title' => ['required'],
            'description' => ['required'],
            'image' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $advertising = Advertising::find($request->id);
        $advertising->edit($request->all());
        $advertising->uploadImage($request->file('image'));

        return response()->json([
            'result' => $advertising
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
            Advertising::find($id)->remove();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => $th->getMessage()
                ], 400);
        }
    }
}
