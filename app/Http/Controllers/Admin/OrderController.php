<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use Carbon\Carbon;
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
        ->with(['user','product', 'userAddress'])
        ->whereDate('created_at', Carbon::today())
        ->get();

        return response()->json([
            'result' => $allOrder
        ], 200);
    }

    public function orderSearch(Request $request)
    {
        $orders = Order::query();
        
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

        $orders = $orders->with(['user','product'])->get();

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
