<?php

namespace App\Http\Controllers;

use App\Order;
use App\BonusSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
            $q->with(['street_r', 'city_r', 'region_r']);
        }])->get();

        try {
            $this->orderSendTelegram($orders);
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            Order::statusPurchased($orders, $request);
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
			
        $bonuses = BonusSystem::all();
        /* https://api.telegram.org/botXXXXXXXXXXXXXXXXXXXXXXX/getUpdates,
        где, XXXXXXXXXXXXXXXXXXXXXXX - токен вашего бота, полученный ранее */
        $token = "982493491:AAH3KSLYX3QHfwIYK5zGu4EPBCQsudq0m7c";
        $chat_id = "-1001364950858";
        $userAddressregion_r = '';
        $userAddresscity_r = '';
        $userAddressstreet_r = '';
        $userAddressRoomNumber = '';
        $userAddressRefPoint = '';
        if(isset($orders[0]['userAddress'])) {
            $userAddressRoomNumber = $orders[0]['userAddress']['room_number'];
            $userAddressRefPoint = $orders[0]['userAddress']['ref_point'];
            $userAddressregion_r = $orders[0]['userAddress']['region_r']['title'];
            $userAddresscity_r = $orders[0]['userAddress']['city_r']['title'];
            $userAddressstreet_r = $orders[0]['userAddress']['street_r']['title'];
				}
				
        $txt = "";
        $arr = [
            'Фойдаланувчи: ' => $orders[0]['user']['phone'],
            'ФИО: ' => $orders[0]['user']['name'] . $orders[0]['user']['surname'],
            'Манзил: ' =>  $userAddressregion_r  . ', ' . $userAddresscity_r . ', ' . $userAddressstreet_r,
            'Мулжал: ' => $userAddressRoomNumber . ', ' . $userAddressRefPoint,
            'Вакти: ' => $orders[0]['delivery_date'] . '|' .  $orders[0]['delivery_time'],
        ];
				
        foreach ($arr as $key => $value) {
            $txt .= "<b>" . $key . "</b> " . $value . "\n";
        };
				
        foreach ($orders as $order) {
            $txt .=  "<b>" . 'Номи:' . "</b> " . 'ID-' . $order->product['id'] . ',' . $order->product['title'] 
            . '|' . $order['quantity'] . '|' .$order->product['price']  . '|' . $order->product['discount'] . '%' . "\n";
        };
        
        $totalPrice = 0;
        foreach ($orders as $order) {
            $totalPrice += (($order->product->price * $order->quantity) - (($order->product->price * $order->quantity) * ($order->product->discount / 100)));
				};
				// $totalPrice = 1128000
				
        $earnFromBonusSystem = 0;
        foreach ($bonuses as $bonus) {
            if($totalPrice > $bonus['price']) {
                if($bonus['price_percentage'] != 0){
                    $earnFromBonusSystem = $totalPrice - ($totalPrice - ($totalPrice * ($bonus['price_percentage'] / 100 )));
                }else {
                    $earnFromBonusSystem = $totalPrice - ($totalPrice - $bonus['price_amount']);
                }
            }
				};

				$payment_type = $orders[0]['payment_type'] == 1 ? 'Да' : 'Нет';
        $txt .= "<b>" . 'Пластик: ' . "</b> " . $payment_type . "\n";
        $txt .= "<b>" . 'Бонус: ' . "</b> " . number_format($earnFromBonusSystem, 0,","," ") . "\n";
        $txt .= "<b>" . 'Делфин хизмати: ' . "</b> " . number_format($orders[0]['userAddress']['street_r']['deliveryCost'], 0,","," ") . "\n";
        $totalPrice = number_format((int)$totalPrice - $earnFromBonusSystem + $orders[0]['userAddress']['street_r']['deliveryCost'], 0,","," ");
        $txt .= "<b>" . 'Жами: ' . "</b> " . $totalPrice . "\n";

        $website="https://api.telegram.org/bot".$token;
        $chatId = $chat_id;
        $params=[
            'chat_id'=>$chatId, 
            'text'=> $txt,
            'parse_mode' => 'html'
        ];
        $ch = curl_init($website . '/sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        //fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}", "r");
    }
}
