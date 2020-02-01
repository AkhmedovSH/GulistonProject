<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount', 'status', 'uniques', 'transacID', 'systemsTraceAuditNumber',
        'reversal', 'code', 'message'
    ];

    public static function add($fields)
    {
        $transaction = new static;
        $transaction->fill($fields);
        $transaction->save();

        return $transaction;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->status = true;
        $this->save();
    }
}
