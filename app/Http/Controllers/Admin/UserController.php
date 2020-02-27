<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        
        $users = User::with(['userAddresses','userCards'])
        ->orderBy('id', 'DESC')
        ->get();
        
        return response()->json([
            'result' => $users
            ], 200);
    }


    public function store(Request $request){
        
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'min:12', 'max:12'],
            'car_info' => ['required'],
            'car_number' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $user = User::addTaxiDriver($request->all());
        
        return response()->json([
            'result' => $user
            ], 200);
    }

    public function userSendNotificationToOne(Request $request){
        $user = User::where('phone', $request->phone)->first();
        $payload = $this->createCardPayload($user, $request);
        $response = $this->curlRequest($payload);

        return response()->json([
            'result' => $response
            ], 200);
    }

    public function createCardPayload($user, $request){
        return [
            'message' => [
                'token' => $user->firebase_token,
                'notification' =>[
                    'body' => $request->body,
                    'title' => $request->title,
                ],
            ],
        ];
    }

    public function curlRequest($payload){
        $apiKey = 'AAAAaj29AhE:APA91bHYxtCHLO3AkxvPouGemyBD5y-QRHQ2tm5JhQWHNhBeLW9DYzBaUz0Bg2UyIGv1SrBoEet86voA6yfX62bhbVTWMqksJZPQLjpbgMy1F-pvScmuB2WgBe_y6qEVZ4AXAFImzvby';
        $headers = ['Authorization: key='. $apiKey, 'Content-Type: application/json'];
        $ch = curl_init("https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
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
}
