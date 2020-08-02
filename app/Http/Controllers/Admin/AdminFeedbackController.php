<?php

namespace App\Http\Controllers\Admin;

use App\User;
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

    public function store(Request $request)
    {
        $feedbacks = AdminFeedbackMessage::add($request->all(), 'admin');

        $feedback = AdminFeedback::where('id', $request->admin_feedback_id)->first();
        $user = User::where('id', $feedback->user_id)->first();

        $payload = $this->createCardPayload($user, $feedback->title, $request->message);
        $response = $this->curlRequest($payload);

        if(isset($response->error)){
            return response()->json([
            'error' => $response
            ], 400);
        }else{
            return response()->json([
            'result' => $response
            ], 200);
        }  
    }

    public function createCardPayload($user, $title, $message){
        return array(
            'to' => $user['firebase_token'],
            'notification' => array('title' => $title, 'body' => $message),
        );
    }

    public function curlRequest($payload){
        $apiKey = 'AIzaSyDQYVOgD-fwZsbyjH0XVR0bfWdWreCP8v0';
        $headers = array('Authorization: key='.$apiKey, 'Content-Type: application/json');
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $body = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $body = substr($body, $headerSize);
        $response = json_decode($body);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }
}
