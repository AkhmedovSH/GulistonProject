<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Http\OrderController;
use Illuminate\Http\Request;
use App\Helpers\GuzzleHelper;

class TransactionController extends Controller
{

    public function paymentWithCard(Request $request)
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
            ],
            'id' =>  '123456qwerty',
        ];

        $rest = new GuzzleHelper();
        $response = $rest->post($payload, 'https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew');

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

    public function confirmPayment(Request $request)
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
