<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount', 'status', 'uniques', 'transaction_id',
        'reversal', 'code', 'message', 'user_id', 'user_card_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public static function add($fields, $userCard = null,  $response)
    {
        $transaction = new static;
        $transaction->fill($fields);
        $transaction->user_id = auth()->user()->id;
        $transaction->uniques = $response->result->uniques;
        $transaction->order_ids = json_encode($fields['order_ids']);
        if($userCard != null){
            $transaction->user_card_id = $userCard->id;
        }
        $transaction->save();

        return $transaction;
    }

    public function edit($fields, $response)
    {
        dd($response);
        $this->fill($fields);
        $this->status = true;
        $this->transacID = $response->result->transacID != null ? $response->result->transacID : 0;
        $this->save();
    }

    public function addError($response)
    {
        $this->code = $response->error->code;
        $this->message = $response->error->message;
        $this->save();
    }
}
