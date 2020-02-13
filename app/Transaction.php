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
        $transaction->uniques = $response->result->uniques;
        $transaction->save();

        return $transaction;
    }

    public function edit($fields, $response)
    {
        $this->fill($fields);
        $this->status = true;
        $this->transacID = $response->result->transacID;;
        $this->systemsTraceAuditNumber = $response->result->systemsTraceAuditNumber;;
        $this->save();
    }

    public function addError($response)
    {
        $this->code = $response->error->code;
        $this->message = $response->error->message;
        $this->save();
    }
}
