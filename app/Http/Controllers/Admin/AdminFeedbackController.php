<?php

namespace App\Http\Controllers\Admin;

use App\AdminFeedback;
use Illuminate\Http\Request;
use App\AdminFeedbackMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = AdminFeedback::orderBy('id', 'DESC')->with('user')->get();

        return response()->json(
            [
                'result' => $feedbacks
            ], 200);
    }

    public function show($id)
    {
        $feedbacks = AdminFeedbackMessage::where('admin_feedback_id', $id)->with(['user', 'admin'])->get();

        return response()->json(
            [
                'result' => $feedbacks
            ], 200);
    }

    public function store(Request $request)
    {
        $feedbacks = AdminFeedbackMessage::add($request->all(), 'admin');
        return response()->json(
            [
                'result' => $feedbacks
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
            AdminFeedback::find($id)->delete();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => $th->getMessage()
                ], 400);
        }
    }
}
