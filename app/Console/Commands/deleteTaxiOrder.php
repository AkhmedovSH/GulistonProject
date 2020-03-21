<?php

namespace App\Console\Commands;

use App\OrderTaxi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class deleteTaxiOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:taxiOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete taxi orders which not taken';

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
        $taxiOrders = OrderTaxi::where('status', 0)->get();
        foreach($taxiOrders as $order){
            $totalDuration = $order->created_at->diffInMinutes(Carbon::now());
            if($totalDuration > 15){
                $order->delete();
            }
        }
    }
}
