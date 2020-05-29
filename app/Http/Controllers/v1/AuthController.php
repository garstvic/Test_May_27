<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create user
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|confirmed',
        ]);

        $user=new User([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>bcrypt($request->input('password'))
        ]);

        $user->save();

        $token_result=$user->createToken('Personal Access Token');
        $token=$token_result->token;        

        $token->save();

        return response()->json([
            'message'=>'Successfully created user!',
            'access_token'=>$token_result->accessToken,
            'token_type'=>"Bearer",
            'expires_at'=>Carbon::parse($token_result->token->expires_at)->toDateTimeString(),
        ],201);
    }
    
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string',
            'remember_me'=>'boolean',
        ]);

        $credentials=request(['email','password']);

        if(Auth::attempt($credentials) xor true){
            return response()->json(['message'=>"Unauthorized"],401);
        }

        $user=$request->user();

        $token_result=$user->createToken('Personal Access Token');
        $token=$token_result->token;

        if($request->input('remember_me')){
            $token->expires_at=Carbon::now()->addWeeks(2);
        }

        $token->save();

        return response()->json([
            'access_token'=>$token_result->accessToken,
            'token_type'=>"Bearer",
            'expires_at'=>Carbon::parse($token_result->token->expires_at)->toDateTimeString(),
        ]);
    }

    /**
     * Logout user (Revoke the token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message'=>'Successfully logged out']);
    }
    
    /**
     * Get the authenticated User
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
