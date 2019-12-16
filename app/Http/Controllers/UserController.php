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
        return $user;
    }


    public function update(Person $user, Request $request)
    {
        $user->update($request->all());
        return $user;
    }

    public function destroy(Person $user){
        $user->delete();
        return response()->json();
    }
}
