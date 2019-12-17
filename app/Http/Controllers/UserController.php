<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function index()
    {
        return response()->json(
            [
                'result' => User::orderby('id', 'ASC')->get()
            ], 200);

    }

    public function show(User $user)
    {
        return response()->json(
            [
                'result' => $user
            ], 200);
    }


    public function update(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'password' => ['nullable', 'min:3','max:255'],
            'image' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $user = $user->update($request->all());
        $user->uploadImage($request->file('image'));
        return response()->json(
            [
                'result' => $user
            ], 200);
    }

    public function destroy(User $user){
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
