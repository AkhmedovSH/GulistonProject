<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxiTransactionHistory extends Model
{
    // status 0 okey 1 canceled
    protected $fillable = [
        'amount', 'taxi_id', 'user_id', 'status',
    ];

    public static function add($fields)
    {
        $transaction = new static;
        $transaction->fill($fields);
        $transaction->user_id = auth()->user()->id;
        $transaction->save();

        return $transaction;
    }
    
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
        return $this;
    }

    public function reversal($id)
    {
        //
        //return $this;
    }
}
