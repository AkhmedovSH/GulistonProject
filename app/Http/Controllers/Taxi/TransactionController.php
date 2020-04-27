<?php

namespace App\Http\Controllers\Taxi;

use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public $key = "A1421A85050462A2A9885D2C089C12";
    public $EposId = "932352";
    public $login = "998972461019";
    public $password = "12345";

    public function checkTransaction(Request $request)
    {
        $payload = $this->createCardPayload($request);

        $response = $this->curlRequest($payload);
        if($response->result != null){
            $transaction = new Transaction();
            $transaction->add($request->all(), null, $response, 'taxi');
        }else{
            return response()->json(
                [
                    'error' => $response->error->message
                ], 400);
        }

        return response()->json(
            [
                'result' => $response->result
            ], 200);
    }

    public function performTransaction(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        if($user != null){
            $payload = $this->createPerformPayload($request);
            
            $response = $this->curlRequest($payload);
            
            $transaction = Transaction::where('uniques', $request->uniques)->first();

            if($response->result != null){
               
                $user->taxi_balance = $user->taxi_balance + $transaction->amount;
                $user->save();
                $transaction->edit($request->all(), $response);
                return response()->json(
                    [
                        'result' => $response->result
                    ], 200);
            }else{
                $transaction->addError($response);
                return response()->json(
                    [
                        'error' => $response->error->message
                    ], 400);
            }
        }
        
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

    public function createCardPayload($request){
        return [
            'params' => [
                'key' => $this->key,
                'EposId' => $this->EposId,
                'phoneNumber' => $request->phone,
                'cardLastNumber' => $request->card,
                'expire' => $request->expire,
                'summa' => $request->amount,
                'orderId' => "",
            ],
            'id' =>  '123456qwerty',
        ];
    }
    
    public function createPerformPayload($request){
        return [
            'params' => [
                'key' => $this->key,
                'EposId' => $this->EposId,
                'phoneNumber' => $request->phone,
                'cardLastNumber' => $request->card,
                'expire' => $request->expire,
                'summa' => $request->amount,
                'orderId' => "",
                'uniques' => $request->uniques,
                'otp' => $request->otp,
            ],
            'id' =>  '123456qwerty',
        ];
    }
}
