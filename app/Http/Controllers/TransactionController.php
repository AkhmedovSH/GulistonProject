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

    public function checkPayment(Request $request)
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
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization: Basic '. base64_encode("998972461019:12345"),
            'charset' => 'UTF-8 '
        ];

       /*  $client = new Client();
        $response = $client->request(
            'POST',
            'https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew',
            [
                'body' => json_encode($payload),
                'header' => $header,
                'debug' => true
            ]
        );
        dd($response->getHeader('Content-Type')); */

        $ch = curl_init("https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, '998972461019' . ":" . '12345');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
       
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        dd($response, $info);
        curl_close($ch);
        

        /* $response = $client->request(
            'GET',
            'https://dolphindelivery.uz/api/getCategories'
        ); */
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
                'key' => $this->login,
                'EposId' => $this->password,
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

        $rest = new Client();
        $response = $rest->request(
            'POST',
            'https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew',
            [
                'auth' => [$this->login,$this->password],
                'body' => json_encode($payload),
            ] 
        );


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
