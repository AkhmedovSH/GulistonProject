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

    public function setFirebaseToken(Request $request){
        
        $user = User::findOrFail(auth()->user()->id);
        $user = $user->setFirebaseToken($request->token);

        return response()->json([
            'result' => $user
            ], 200);
    }

    public function userShow(){
        
        $user = User::findOrFail(auth()->user()->id);
        
        return response()->json([
            'result' => $user
            ], 200);
    }

    public function userFavorite()
    {
        $favorites = UserFavorite::where('user_id', auth()->user()->id)->with('product')->get();
 
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
        
        $userAddress = UserAddress::where('user_id',auth()->user()->id)->with('region_r', 'city_r', 'street_r')->get();
        
        return response()->json([
            'result' => $userAddress
            ], 200);
    }

    public function userAddressAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'min:12', 'max:12'],
            'default' => ['nullable'],
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
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:15'],
            'postal_code' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $userAddresses = UserAddress::where('user_id', auth()->user()->id)->get();
        foreach($userAddresses as $address){
            $address->is_default = 0;
            $address->save();
        }

        $userAddress = UserAddress::find($request->id);
        $userAddress = $userAddress->edit($request->all());
        return response()->json(
            [
                'result' => $userAddress
            ], 200);
    }

    public function userAddressDelete($id){
         UserAddress::find($id)->delete();

        return response()->json([
            'errors' => 'success'
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

    public function userFavoriteDelete($product_id)
    {    
        $userFavorite = UserFavorite::where('user_id', auth()->user()->id)
        ->where('product_id', $product_id)->first();
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
            'errors' => 'success'
            ], 200);
    }
}
