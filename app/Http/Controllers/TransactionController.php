<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GuzzleHelper;

class TransactionController extends Controller
{
    public function payment(Request $request)
    {
        $payload = [
            'params' => [
                'key' => env('UZCARD_KEY', null),
                'EposId' => env('UZCARD_EPOSID', null),
                'phoneNumber' => $request->phoneNumber,
                'cardLastNumber' => $request->cardLastNumber,
                'expire' => $request->expire,
                'summa' => $request->summa,
                'orderId' => $request->orderId,
            ],
            'id' =>  '123456qwerty',
        ];

        $rest = new GuzzleHelper();
        $response = $rest->post($payload, 'https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew');

        return response()->json(
            [
                'result' => $response
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
                'orderId' => $request->orderId,
                'uniques' => $request->uniques,
                'otp' => $request->otp,
            ],
            'id' =>  '123456qwerty',
        ];

        $rest = new GuzzleHelper();
        $response = $rest->post($payload, 'https://myuzcard.uz/api/PaymentBusiness/paymentsWithOutRegistrationNew');

        return response()->json(
            [
                'result' => $response
            ], 200);
    }
}
