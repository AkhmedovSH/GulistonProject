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

    public function show($id){
        
        $user = User::find($id);
        
        return response()->json([
            'result' => $user
            ], 200);
    }

    public function userUpdate(Request $request){
        
        $user = User::where('id', $request->id)->first();
        $user = $user->edit($request->all());

        return response()->json([
            'result' => $user
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

        if(isset($response->error)){
          return response()->json([
            'error' => $response->error->message
            ], 400);
        }else{
          return response()->json([
            'result' => $response
            ], 200);
        }
        
    }

    public function createCardPayload($user, $request){
        return array(
            'to' => $user['firebase_token'],
            'notification' => array('title' => $request->title,'body' => $request->body),
        );
    }

    public function curlRequest($payload){
        $apiKey = 'AIzaSyDQYVOgD-fwZsbyjH0XVR0bfWdWreCP8v0';
        $headers = array('Authorization: key='.$apiKey, 'Content-Type: application/json');
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
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
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response;
    }
}
