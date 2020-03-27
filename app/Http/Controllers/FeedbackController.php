<?php

namespace App\Http\Controllers;

use App\AdminFeedback;
use Illuminate\Http\Request;
use App\AdminFeedbackMessage;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = AdminFeedback::where('user_id', auth()->user()->id)
        ->orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $feedbacks
            ], 200);
    }

    public function storeFeedback(Request $request)
    {
        $feedback = AdminFeedback::add($request->all());
        AdminFeedbackMessage::create([
            'admin_feedback_id' =>  $feedback->id,
            'message' => $feedback->description,
            'user_id' => auth()->user()->id
        ]);

        return response()->json(
            [
                'result' => $feedback
            ], 200);
    }

    public function storeFeedbackMessage(Request $request)
    {
        $feedbacks = AdminFeedbackMessage::add($request->all(), 'user');
        return response()->json(
            [
                'result' => $feedbacks
            ], 200);
    }

    public function show($id)
    {
        $feedbacks = AdminFeedbackMessage::where('admin_feedback_id', $id)
        ->with(['user', 'admin'])->get();

        return response()->json(
            [
                'result' => $feedbacks
            ], 200);
    }

    public function isRead(Request $request)
    {
        $feedbacks = AdminFeedback::where('id', $request->id)->first();
        $feedbacks->is_read = 1;
        $feedbacks->save();        

        return response()->json(
            [
                'result' => $feedbacks
            ], 200);
    }
}
