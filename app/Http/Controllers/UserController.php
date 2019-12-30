<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
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
