<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::all();

        $allOrder = $order->map(function ($order) {
            return [
                "id" => $order->id,
                "longitude" => $order->longitude,
                "latitude" => $order->latitude,
                "time" => $order->time,
                "status" => $order->status,
                "status_text" => $order->status_text,
                "product_id" => $order->product_id,
                "user_id" => $order->user_id,
                "created_at" => $order->created_at,
            ];
        });

        return response()->json([
            'result' => $allOrder
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'price' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $order = Order::add($request->all());
        $order->uploadImage($request->file('image'));
        $order->uploadMultipleImages($request->file('images'));
        
        return response()->json([
            'result' => $order
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
        $order = Order::find($id);
        return response()->json([
            'result' => $order
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required'],
            'image' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $order = Order::find($id);
        $order->edit($request->all());
        $order->uploadImage($request->file('image'));
        
        return response()->json([
            'result' => $order
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
            Order::find($id)->remove();
            return response()->json([
                'success' => 'Deleted'
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Cannot delete'
                ], 400);
        }
    }
}
