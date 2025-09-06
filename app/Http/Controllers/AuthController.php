<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:6']);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(['user'=>$user,'token'=>$token],201);
    }

    public function login(Request $request){
        $credentials = $request->only('email','password');
        if(!$token = JWTAuth::attempt($credentials)){
            return response()->json(['error'=>'Invalid credentials'],401);
        }
        return response()->json(['token'=>$token]);
    }

    public function me(){
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message'=>'Logged out']);
    }
}
