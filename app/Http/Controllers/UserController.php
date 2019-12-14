<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;

class UserController extends Controller
{
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function index(): UserResourceCollection
    {
        return new UserResourceCollection(User::orderby('id', 'ASC')->paginate());
    }


    public function store(Request $request)
    {
       $request->validate([
           'first_name' => 'required',
           'last_name' => 'required',
           'email' => 'required',
           'phone' => 'required',
           'city' => 'required'
       ]);

       $user = User::create($request->all());
       
        return new UserResource($user);
    }


    public function update(Person $user, Request $request): UserResource
    {

        $user->update($request->all());
        return new UserResource($user);
    }

    public function destroy(Person $user){
        $user->delete();
        return response()->json();
    }
}
