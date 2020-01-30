<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\Company;
use App\Product;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function ProjectStatistics()
    {
        $dateStart = Carbon::now()->subMonth(3);
		$dateEnd = Carbon::now();
        $activeUsers = User::whereBetween('created_at',[$dateStart, $dateEnd])->count();
        
        $users = User::count();
        $completedOders = Order::count();
        $products = Product::count();
        $companies = Company::count();
        $categories = Category::count();
        $failedOders = Order::where('status', 0 )->count();

        return response()->json([
            'result' => 
            [
                'users' => $users,
                'activeUsers' => $activeUsers,
                'completedOders' => $completedOders,
                'failedOders' => $failedOders,
                'products' => $products,
                'companies' => $companies,
                'categories' => $categories,
            ]
        ], 200);
    }
}
