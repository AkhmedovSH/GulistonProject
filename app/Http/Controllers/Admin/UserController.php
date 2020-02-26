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
}
