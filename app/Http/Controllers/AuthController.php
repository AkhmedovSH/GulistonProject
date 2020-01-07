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
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

/*     protected function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|min:12|max:12',
            'password' => 'required|min:3'
        ]);

        try {
            $user = User::create([
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Phone is unique, user already created!',
            ]);
        }

        return response()->json(
            [
                'result' => $user
            ], 200);
    } */


    protected function register($credentials)
    {
        try {
            $user = User::create([
                'phone' => $credentials['phone'],
                'password' => Hash::make($credentials['password']),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Phone is unique, user already created!',
            ]);
        }

        $token = auth()->attempt($credentials);
        return $this->respondWithToken($token);
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
            $this->register($credentials);
        }

        $user = auth()->user();
        $user->last_login = Carbon::now();
        $user->save();
        return $this->respondWithToken($token);
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

        return response()->json(['success' => 'Successfully logged out']);
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
}
