<?php

namespace App\Http\Controllers;

use App\Transaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\OrderController;

class TransactionController extends Controller
{
    public $key = "A1421A85050462A2A9885D2C089C12";
    public $EposId = "932352";
    public $login = "998972461019";
    public $password = "12345";

    public function createСheck(Request $request)
    {
        $payload = [
            'params' => [
                'key' => $this->key,
                'EposId' => $this->EposId,
                'phoneNumber' => $request->phoneNumber,
                'cardLastNumber' => $request->cardLastNumber,
                'expire' => $request->expire,
                'summa' => $request->summa,
                'orderId' => "1",
            ],
            'id' =>  '123456qwerty',
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $rest = new Client();
        $response = $rest->request(
            'POST',
            'https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew',
            [
                'auth' => [env('UZCARD_LOGIN'), env('UZCARD_PASSWORD')],
                'header' => $header,
                'body' => json_encode($payload),
            ] 
        );

        dd($response->curl_info);
        if($response['result'] != null){
            $transaction = new Transaction();
            $transaction->add($request->all());
        }else{
            return response()->json(
                [
                    'error' => $response['message']
                ], 200);
        }

        return response()->json(
            [
                'result' => $response['result']
            ], 200);
    }

    public function performPayment(Request $request)
    {
        $payload = [
            'params' => [
                'key' => env('UZCARD_KEY', null),
                'EposId' => env('UZCARD_EPOSID', null),
                'phoneNumber' => $request->phoneNumber,
                'cardLastNumber' => $request->cardLastNumber,
                'expire' => $request->expire,
                'summa' => $request->summa,
                'orderId' => "",
                'uniques' => $request->uniques,
                'otp' => $request->otp,
            ],
            'id' =>  '123456qwerty',
        ];

        $rest = new GuzzleHelper();
        $response = $rest->post($payload, 'https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew');


        if($response['result'] != null){
            $transaction = Transaction::where('uniques', $request->uniques)->first();
            $transaction->update($request->all());
        }else{
            return response()->json(
                [
                    'error' => $response['message']
                ], 200);
        }

        return response()->json(
            [
                'result' => $response['result']
            ], 200);
    }
}
