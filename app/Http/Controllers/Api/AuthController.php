<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        $userData = $request->validate([
            'name'  =>  'required|max:60',
            'email' =>  'email|required|unique:users',
            'password'  =>  'required|confirmed'
        ]);

        $userData['password']  =   bcrypt($request->password);
        
        $user = User::create( $userData );
        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'user'  => $user,
            'accessToken'   => $accessToken
        ]);
    }

    public function login (Request $request) {
        $loginData = $request->validate([
            'email' =>  'email|required',
            'password'  =>  'required'
        ]);

        if(!auth()->attempt($loginData)){
            return response(['message'  =>  'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('auth')->accessToken;

        return response([
            'user'  => auth()->user(),
            'accessToken'   => $accessToken
        ]);
    }


}
