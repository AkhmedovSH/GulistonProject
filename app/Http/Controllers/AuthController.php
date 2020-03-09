<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'loginTaxi', 'checkAdmin']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'min:12', 'max:12'],
            'password' => ['required', 'min:3'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }
        
        $credentials = request(['phone', 'password']);
       
        if (! $token = auth()->attempt($credentials)) {

            //If login not found register user
            $userWithLogin = User::where('phone', $credentials['phone'])->first();
            if($userWithLogin == null){
                try {
                    User::create([
                        'phone' => $credentials['phone'],
                        'password' => Hash::make($credentials['password']),
                    ]);
                    $token = auth()->attempt($credentials);
                } catch (\Throwable $th) {
                    return response()->json([
                        'error' => $th->getMessage()
                        ], 400);
                }
            }

            $userWithPassword = User::where('password',  Hash::make($credentials['password']))->first();
            if($userWithLogin != null && $userWithPassword == null){
                return response()->json([
                    'error' => 'Паролингиз хато.'
                    ], 400);
            }
            
            $user = User::where('phone', $credentials['phone'])
            ->where('password',  Hash::make($credentials['password']))->first();
            if($user){
                $token = auth()->attempt($credentials);
            }         
        }
       
        $user = auth()->user();
        $user->last_login = Carbon::now();
        $user->save();
        return $this->respondWithToken($token);
    }

    public function loginTaxi(Request $request){
       
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'min:12', 'max:12'],
            'password' => ['required', 'min:3'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $credentials = request(['phone', 'password']);
        if (! $token = auth()->attempt($credentials)) {
           
            //If login not found register user
            $userWithLogin = User::where('phone', $credentials['phone'])->first();
            if($userWithLogin == null){
                return response()->json([
                    'error' => 'Фойдаланувчи топилмади'
                    ], 400);
            }

            $userWithPassword = User::where('password',  Hash::make($credentials['password']))->first();
            if($userWithLogin != null && $userWithPassword == null){
                return response()->json([
                    'error' => 'Паролингиз хато.'
                    ], 400);
            }
            
            $user = User::where('phone', $credentials['phone'])
            ->whereIn('type', [1,2])
            ->where('password',  Hash::make($credentials['password']))
            ->first();
            
            if($user){
                $token = auth()->attempt($credentials);
            }         
        }
        if(auth()->user()->type == 1 || auth()->user()->type == 2){
            $user = auth()->user();
            $user->last_login = Carbon::now();
            $user->save();
            return $this->respondWithToken($token);
        }else{
            return response()->json([
                'error' => 'Бу фойдаланувчи таксист эмас'
                ], 400);
        }
        
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(
            [
                'result' => auth()->user()
            ], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['success' => true]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            "result" => 
                [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ], 200);
    }


    public function checkAdmin(Request $request)
    {

        $user = User::where('phone', $request->phone)
            ->where('type', 2)->first();
        if($user != null){
            return response()->json(
                [
                    'result' => $user
                ], 200);
        }else{
            return response()->json(
                [
                    'error' => 'Телефон раками хато, йоки сиз админ эмассиз!'
                ], 400);
        }
    }
}
