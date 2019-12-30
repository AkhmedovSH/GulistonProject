<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function userShow(){
        
        $user = User::with(['orders', 'userAddresses'])->findOrFail(auth()->user()->id);
        
        return response()->json([
            'result' => $user
            ], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:15'],
            'surname' => ['nullable', 'string', 'max:15'],
            'email' => ['nullable', 'email'],
            'password' => ['nullable', 'min:3','max:30'],
            'image' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $user = auth()->user();
        $user->edit($request->all());
        $user->uploadImage($request->file('image'));
        return response()->json(
            [
                'result' => $user
            ], 200);
    }

    public function userAddressAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:15'],
            'phone' => ['nullable', 'string', 'max:15'],
            'street' => ['nullable'],
            'state' => ['nullable'],
            'city' => ['nullable'],
            'postal_code' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $user_address = new UserAddress;
        $user_address = $user_address->add($request->all());
        return response()->json(
            [
                'result' => $user_address
            ], 200);
    }

    public function userAddressUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['nullable', 'string', 'max:15'],
            'phone' => ['nullable', 'string', 'max:15'],
            'street' => ['nullable'],
            'state' => ['nullable'],
            'city' => ['nullable'],
            'postal_code' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $user_address = UserAddress::find($request->id);
        $user_address = $user_address->edit($request->all());
        return response()->json(
            [
                'result' => $user_address
            ], 200);
    }

    public function destroy(){
        
        $user = auth()->user();
        try {
            $user->remove();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Cannot delete'
                ], 400);
        }
        
        return response()->json([
            'errors' => $user
            ], 200);
    }
}
