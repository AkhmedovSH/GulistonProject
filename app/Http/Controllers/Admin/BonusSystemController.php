<?php

namespace App\Http\Controllers\Admin;

use App\BonusSystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BonusSystemController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBonus = BonusSystem::orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $allBonus
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {  
        $bonus = BonusSystem::add($request->all());
        return response()->json(
            [
                'result' => $bonus
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
        $bonus = BonusSystem::find($id);
        return response()->json([
                'result' => $bonus
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
        $bonus = BonusSystem::find($request->id);
        $bonus->edit($request->all());

        return response()->json([
            'result' => $bonus
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
        BonusSystem::find($id)->delete();
            return response()->json([
                'success' => true
            ], 200);
    }
}
