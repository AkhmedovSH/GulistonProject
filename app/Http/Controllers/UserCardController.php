<?php

namespace App\Http\Controllers;

use App\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCardController extends Controller
{
    public function index()
    {
        $allCards = UserCard::where('user_id', auth()->user()->id)
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json([
            'result' => $allCards
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:12'],
            'card' => ['required', 'string', 'max:16'],
            'expire' => ['required', 'string', 'max:4'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        try {
            $userCard = UserCard::add($request->all());
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => 'Бу карта раками кушилган!'
                ], 400);
        }

        return response()->json([
            'result' => $userCard
        ], 200);
    }

   /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userCard = UserCard::find($id);
        return response()->json([
            'result' => $userCard
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:12'],
            'card' => ['required', 'string', 'max:16'],
            'expire' => ['required', 'string', 'max:4'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $userCard = UserCard::find($request->id);

        try {
            $userCard->edit($request->all());
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => 'Бу карта раками кушилган!'
                ], 400);
        }
        
        return response()->json([
            'result' => $userCard
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $userCard = UserCard::find($id)->delete();
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => $th->getMessage()
                ], 400);
        }

        return response()->json([
            'success' => true
            ], 200);
    }
}
