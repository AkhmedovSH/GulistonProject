<?php

namespace App\Console\Commands;

use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class deleteFromCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:fromCart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete from cart producst which not ordered clear DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cartOrders = Order::where('order_number', NULL)->get();
        foreach($cartOrders as $order){
            $totalDuration = $order->created_at->diffInDays(Carbon::now());
            if($totalDuration > 30){
                $order->delete();
            }
        }
    }
}
