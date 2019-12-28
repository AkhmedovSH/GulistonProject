<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cart()
    {
        $allOrder = Order::orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $allOrder
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required'],
            'user_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $order = Order::add($request->all());
        
        return response()->json([
            'result' => $order
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cartUpdate(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $product = Order::find($request->id);
        $product->edit($request->all());

        return response()->json([
            'result' => $product
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cartDestroyOne(Order $order)
    {
        try {
            $order->delete();
            return response()->json([
                'success' => true
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Cannot delete'
                ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cartDestroyAll(Request $request)
    {
        try {
            $orders = Order::find($request->id)->get();
            Order::deleteAll($orders);
            return response()->json([
                'success' => true
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Cannot delete'
                ], 400);
        }
    }
}
