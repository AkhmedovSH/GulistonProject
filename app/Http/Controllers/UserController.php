<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAddress;
use App\UserFavorite;
use App\AdminFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function userShow(){
        
        $user = User::findOrFail(auth()->user()->id);
        
        return response()->json([
            'result' => $user
            ], 200);
    }

    public function userFavorite()
    {
        $favorites = UserFavorite::where('user_id', auth()->user()->id)->with('product')->get();
        //I have to do foreach becouse favorites collection for decoding parameters
        //dd($favorites[0]['parameters']);
        //$favorites = json_decode($favorites['parameters']);
 
        return response()->json([
            'result' => $favorites
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

    public function userAddress(){
        
        $userAddress = UserAddress::where('user_id',auth()->user()->id)->first();
        
        return response()->json([
            'result' => $userAddress
            ], 200);
    }

    public function userAddressAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:15'],
            'phone' => ['nullable', 'string', 'min:12', 'max:12'],
            'default' => ['nullable'],
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

        $userAddress = new UserAddress;
        $userAddress = $userAddress->add($request->all());
        return response()->json(
            [
                'result' => $userAddress
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

        $userAddress = UserAddress::find($request->id);
        $userAddress = $userAddress->edit($request->all());
        return response()->json(
            [
                'result' => $userAddress
            ], 200);
    }

    public function userFavoriteAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $exist = UserFavorite::where('user_id', auth()->user()->id)
        ->where('product_id', $request->product_id)->first();
        
        if($exist != null){
            return response()->json(
                [
                    'error' => 'Already added!'
                ], 400);
        }

        $userFavorite = new UserFavorite;
        $userFavorite = $userFavorite->add($request->all());
        return response()->json(
            [
                'result' => $userFavorite
            ], 200);
    }

    public function userFavoriteDelete(Request $request)
    {    
        $userFavorite = UserFavorite::where('user_id', auth()->user()->id)
        ->where('product_id', $request->product_id)->first();
        $userFavorite->delete();

        return response()->json([
            'success' => true
            ], 200);
    }


    public function userRequestToAdmin(Request $request)
    {    
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $adminFeedback = new AdminFeedback;
        $adminFeedback = $adminFeedback->add($request->all());

        return response()->json([
            'result' => $adminFeedback
            ], 200);
    }

    public function destroy(){
        
        $user = auth()->user();
        $user->remove();

        return response()->json([
            'errors' => $user
            ], 200);
    }
}
