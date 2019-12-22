<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function ProjectStatistics()
    {
        $users = User::count();
        $activeUsers = User::count();
        $oders = Order::count();

        return response()->json([
            'users' => $users,
            'activeUsers' => $activeUsers,
            'oders' => $oders
        ], 200);
    }
}
