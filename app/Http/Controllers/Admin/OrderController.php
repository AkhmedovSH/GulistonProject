<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allOrder = Order::orderBy('id', 'DESC')
        ->where('status', 1)
        ->with(['user','product'])
        ->paginate(25);

        return response()->json([
            'result' => $allOrder
        ], 200);
    }

    public function orderSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $orders =  Order::where('order_number', 'LIKE', "%$request->order_number%")
        ->with(['user','product'])->paginate(25);

        return response()->json(
            [
                'result' => $orders
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
        $order = Order::find($id);
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
                'success' => true
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
                ], 400);
        }
    }
}
