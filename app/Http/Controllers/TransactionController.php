<?php

namespace App\Http\Controllers;

use App\UserCard;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public $key = "A1421A85050462A2A9885D2C089C12";
    public $EposId = "932352";
    public $login = "998972461019";
    public $password = "12345";

    public function checkTransaction(Request $request)
    {
        $userCard = UserCard::where('id', $request->user_card_id)
        ->OrWhere('phone', $request->phone)
        ->first();

        if($userCard != null){
            $payload = $this->createCardPayload($userCard, $request);
        }else{
            try {
                $userCard = new UserCard();
                $userCard->add($request->all());
            } catch (\Throwable $th) {
                return response()->json(
                    [
                        'error' => 'Бу карта раками кушилган!'
                    ], 400);
            }
            $payload = $this->createCardPayload($userCard, $request);
        }
        
        $response = $this->curlRequest($payload);
        if($response->result != null){
            $transaction = new Transaction();
            $transaction->add($request->all(), $response);
        }else{
            return response()->json(
                [
                    'error' => $response->error->message
                ], 200);
        }

        return response()->json(
            [
                'result' => $response->result
            ], 200);
    }

    public function performTransaction(Request $request)
    {
        $userCard = UserCard::where('phone', $request->phone)->first();

        $payload = $this->createPerformPayload($userCard, $request);
        
        $response = $this->curlRequest($payload);

        if($response->result != null){
            $transaction = Transaction::where('uniques', $request->uniques)->first();
            $transaction->edit($request->all(), $response);
        }else{
            return response()->json(
                [
                    'error' => $response->error->message
                ], 200);
        }

        return response()->json(
            [
                'result' => $response->result
            ], 200);
    }

    public function curlRequest($payload){
        $ch = curl_init("https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->login . ":" . $this->password);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $body = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $body = substr($body, $headerSize);
        $response = json_decode($body);
        curl_close($ch);

        return $response;
    }

    public function createCardPayload($userCard, $request){
        return [
            'params' => [
                'key' => $this->key,
                'EposId' => $this->EposId,
                'phoneNumber' => $userCard->phone,
                'cardLastNumber' => $userCard->card,
                'expire' => $userCard->expire,
                'summa' => $request->amount,
                'orderId' => "",
            ],
            'id' =>  '123456qwerty',
        ];
    }
    public function createPerformPayload($userCard, $request){
        return [
            'params' => [
                'key' => $this->login,
                'EposId' => $this->password,
                'phoneNumber' => $userCard->phone,
                'cardLastNumber' => $userCard->card,
                'expire' => $userCard->expire,
                'summa' => $request->amount,
                'orderId' => "",
                'uniques' => $request->uniques,
                'otp' => $request->otp,
            ],
            'id' =>  '123456qwerty',
        ];
    }
}

