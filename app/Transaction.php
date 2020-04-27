<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount', 'status', 'uniques', 'transaction_id',
        'reversal', 'code', 'message', 'user_id', 'user_card_id', 'payed_for'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public static function add($fields, $userCard = null,  $response, $payed_for)
    {   
        $transaction = new static;
        $transaction->fill($fields);
        if(isset($payed_for)){
            $transaction->payed_for = $payed_for;
        }
        $transaction->user_id = auth()->user()->id;
        $transaction->uniques = $response->result->uniques;
        if(isset($fields['order_ids'])){
            $transaction->order_ids = json_encode($fields['order_ids']);
        }
        
        if($userCard != null){
            $transaction->user_card_id = $userCard->id;
        }
        $transaction = $transaction->save();
       
        return $transaction;
    }

    public function edit($fields, $response)
    {
        $this->fill($fields);
        $this->status = 1;
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
