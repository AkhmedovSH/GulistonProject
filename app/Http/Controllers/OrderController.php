<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function cartIndex()
    {
        $allOrder = Order::where('user_id', auth()->user()->id)->where('status', 0)->orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $allOrder
            ], 200);
    }

    public function cartAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => ['required'],
            'product_id' => ['required']
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

    public function cartUpdate(Request $request)
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
       
        $order = Order::find($request->id);
        $order->edit($request->all());

        return response()->json([
            'result' => $order
            ], 200);
    }

    public function cartDestroyOne($id)
    {
        
        try {
            $order = Order::findOrFail($id);
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

    public function cartDestroyAll()
    {
        try {
            $orders = Order::where('user_id', auth()->user()->id)->get();
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

    public function orderIndex()
    {
        $allOrder = Order::where('user_id', auth()->user()->id)->where('status', 1)->orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $allOrder
            ], 200);
    }

    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $orders = Order::where('user_id', auth()->user()->id)->get();
        Order::statusPurchased($orders, $request->address_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
