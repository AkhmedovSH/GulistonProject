<?php

namespace App\Http\Controllers\Admin;

use App\OrderTaxi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderTaxiController extends Controller
{
    public function index()
    {
        $allTaxiOrders = OrderTaxi::orderBy('id', 'DESC')->with(['user', 'taxi_driver'])->paginate(20);
        return response()->json([
            'result' => $allTaxiOrders
        ], 200);
    }

    public function orderSearch(Request $request)
    {
        $orders = OrderTaxi::query();
        
        if ($request->order_number) {
            $orders = $orders->where('order_number', 'LIKE', "%$request->order_number%");
        }

        if ($request->phone) {
            $user = User::where('phone', $request->phone)->first();
            if($user != null){
                $orders = $orders->where('user_id', $user->id);
            }
        }

        if ($request->beginDate && $request->endDate) {
            $orders = $orders->whereBetween('created_at', [$request->beginDate, $request->endDate]);
        }

        if ($request->beginDate) {
            $orders = $orders->whereDate('created_at', $request->beginDate);
        }

        $orders = $orders->with(['user','taxi_driver'])->get();

        return response()->json(
            [
                'result' => $orders
            ], 200);
    }

}
