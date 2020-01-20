<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function getCart()
    {
        $allOrder = Order::where('user_id', auth()->user()->id)
        ->where('status', 0)
        ->orderBy('id', 'DESC')
        ->with('product')
        ->get();

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
            'product_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $order = Order::where('product_id', $request->product_id)->where('user_id', auth()->user()->id)->first();
        $order = $order->edit($request->all());

        return response()->json([
            'result' => $order
            ], 200);
    }

    public function cartDeleteOne($id)
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

    public function cartDeleteAll()
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

    public function getOrders()
    {
        $allOrder = Order::where('user_id', auth()->user()->id)->where('status', 1)
        ->with(['user', 'userAddress', 'product'])
        ->orderBy('id', 'DESC')->paginate(20);

        return response()->json(
            [
                'result' => $allOrder
            ], 200);
    }

    public function orderCreate(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'address_id' => ['required'],
            'product_ids' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $orders = Order::where('user_id', auth()->user()->id)
        ->where('status', 0)
        ->whereIn('product_id', $request->product_ids)
        ->with('user', 'product')
        ->get();
        try {
            //Order::statusPurchased($orders, $request->address_id);
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $this->orderSendTelegram($orders);
        } catch (\Throwable $th) {
            //throw $th;
        }
        
       
        return response()->json([
            'success' => $orders
        ], 200);
    }

    public function orderAccepted()
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $order = Order::where('user_id', auth()->user()->id)->findOrFail($request->product_id);
        $order->statusAccepted();
        return response()->json(
            [
                'result' => $order
            ], 200);
    }

    public function orderRejected()
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $order = Order::where('user_id', auth()->user()->id)->findOrFail($request->product_id);
        $order->statusRejected();
        return response()->json(
            [
                'result' => $order
            ], 200);
    }

    public function orderSendTelegram($orders)
    {
        /* https://api.telegram.org/botXXXXXXXXXXXXXXXXXXXXXXX/getUpdates,
        где, XXXXXXXXXXXXXXXXXXXXXXX - токен вашего бота, полученный ранее */
        $token = "982493491:AAH3KSLYX3QHfwIYK5zGu4EPBCQsudq0m7c";
        $chat_id = "-329561281";
        foreach ($orders as $order) {
            
            $arr = array(
                'Заказ раками: ' => $order['id'],
                'Микдори: ' => $order['quantity'],
                'Фойдаланувчи: ' => $order->user['phone'],
                'Название: ' => $order->product['title'],
                'Цена: ' => $order->product['price'],
                'Скидка: ' => $order->product['discount'],
            );
            $txt = "";
            foreach ($arr as $key => $value) {
                $txt .= "<b>" . $key . "</b> " . $value . "%0A";
            };

            fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}", "r");

        };
    }

    public function orderSendMail($orders)
    {
        $name = $request->name;
        $phone = $request->phone;
        $city = $request->city;
        $country = $request->country;
        $street = $request->street;
        $postcode = $request->postcode;
        $to_name = $request->name;
        $to_email = 'info.garminuz@gmail.com';//shurikaxmedov1@gmail.com
        $data = array('name' => $name, "phone" => $phone ,
                     'city' => $city, "country" => $country,
                     'street' => $street, "postcode" => $postcode);
        Mail::send('emails.mail', ["info"=>$data], function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('DolphinDelivery.uz | Сизнинг янги заказларингиз');
            $message->from('shurikaxmedov1@gmail.com','Хурмат билан');
        });
    }
}
