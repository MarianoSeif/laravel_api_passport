<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\JsonResponse;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'=>'required|string|max:55',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed'
        ]);

        $validatedData['password']=bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;
            
        return response([
            'user'=>$user,
            'acces_token'=>$accessToken
        ]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(!auth()->attempt($loginData)){
            return response(['message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
            'user'=>auth()->user(),
            'acces_token'=>$accessToken
        ]);
    }
}
