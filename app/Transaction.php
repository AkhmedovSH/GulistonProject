<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount', 'status', 'uniques', 'transaction_id',
        'reversal', 'code', 'message', 'user_id'
    ];

    public static function add($fields, $response)
    {
        $transaction = new static;
        $transaction->fill($fields);
        $transaction->user_id = auth()->user()->id;
        $transaction->save();

        return $transaction;
    }

    public function edit($fields, $response)
    {
        $this->fill($fields);
        $this->status = true;
        $this->save();
    }
}
