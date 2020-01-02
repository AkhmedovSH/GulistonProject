<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        
        $users = User::with(['userAddresses'])->get();
        
        return response()->json([
            'result' => $users
            ], 200);
    }
}
