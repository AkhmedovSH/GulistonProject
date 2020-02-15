<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\GuzzleHelper;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allTransactions = Transaction::with('user')->orderBy('id', 'DESC')->paginate(25);

        return response()->json([
            'result' => $allTransactions
        ], 200);
    }
    
    public function transactionSearch(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        $transactions =  Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(25);


        return response()->json(
            [
                'result' => $transactions
            ], 200);
    }

    public function reversal($id)
    {
        $transaction = Transaction::where('id', $id)->first();
        $payload = [
            'key' => env('UZCARD_KEY', null),
            'eposId' => env('UZCARD_EPOSID', null),
            'summa' => $transaction->summa,
            'phoneNumber' => $transaction->phoneNumber,
            'uniques' => $transaction->uniques,
        ];

        $rest = new GuzzleHelper();
        $response = $rest->post($payload, 'https://myuzcard.uz/api/payment/reversal');

        return response()->json(
            [
                'result' => $response
            ], 200);
    }
}
