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
            'order_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
       
        $order = Order::where('id', $request->order_id)->where('user_id', auth()->user()->id)->first();
        $order = $order->edit($request->all());

        return response()->json([
            'result' => $order
            ], 200);
    }

    public function cartDeleteOne($id)
    {
        
        try {
            $order = Order::findOrFail($id)->delete();
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

    public function cartDeleteAll()
    {
        try {
            $orders = Order::where('user_id', auth()->user()->id)
            ->where('status', 0)
            ->get();
            Order::deleteAll($orders);
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

    public function getOrders()
    {
        $allOrder = Order::where('user_id', auth()->user()->id)->where('status', 1)
        ->with(['product', 'user', 'userAddress'])
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
            'order_ids' => ['required'],
            'delivery_date' => ['required'],
            'delivery_time' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $orders = Order::where('user_id', auth()->user()->id)
        ->where('status', 0)
        ->whereIn('id', $request->order_ids)
        ->with(['product', 'user'])
        ->with(['userAddress' => function($q) {
            $q->with('streetR', 'cityR', 'regionR');
        }])->get();
        try {
            Order::statusPurchased($orders, $request);
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $this->orderSendTelegram($orders);
        } catch (\Throwable $th) {
            throw $th;
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
        $chat_id = "-1001364950858";
       
        
        foreach ($orders as $order) {
            $userAddressCity = '';
            $userAddressStreet = '';
            $userAddressRoomNumber = '';
            $userAddressRefPoint = '';

            $userAddressRegionR = '';
            $userAddressCityR = '';
            $userAddressStreetR = '';


            if($order->userAddress != null){
                $userAddressCity = $order->userAddress->city != null ? $order->userAddress->city : '';
                $userAddressStreet = $order->userAddress->street != null ? $order->userAddress->street : '';
                $userAddressRoomNumber = $order->userAddress->room_number != null ? $order->userAddress->room_number : '';
                $userAddressRefPoint = $order->userAddress->ref_point != null ? $order->userAddress->ref_point : '';
            }
            if($order->userAddress != null){
                if($order->userAddress->city != null) {
                    $userAddressRegionR = $order->userAddress->regionR != null ? $order->userAddress->regionR->title : '';
                    $userAddressCityR = $order->userAddress->cityR != null ? $order->userAddress->cityR->title : '';
                    $userAddressStreetR = $order->userAddress->streetR != null ? $order->userAddress->streetR->title : '';
                }
            }

            $arr = [
                'Фойдаланувчи: ' => $order->user['phone'],
                'Заказ раками: ' => 'OID' . $order['id'] . '_PID' . $order->product['id'],
                'Номи: ' => $order->product['title'] . '|' . $order['quantity'] . '|' .$order->product['price'],
                'Манзил: ' => $userAddressCity  . ', ' . $userAddressStreet . ', ' . $userAddressRoomNumber . ', ' . $userAddressRefPoint,
                'Манзил2: ' =>  $userAddressRegionR  . ', ' . $userAddressCityR . ', ' . $userAddressStreetR,
                'Вакти: ' => $order->delivery_date . '|' . $order->delivery_time,
            ];
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
