<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request){
        $validated=$request->validated();
        
        $user=User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=>Hash::make($validated['password']),
            'role'=>$validated['role']??'employee',
        ]);
        $token=$user->createToken('auth-token')->plainTextToken;

        return ApiResponse::sendResponse([
            'user'=>$user,
            'token'=>$token,
        ],'User created successfully',201);
    }
    public function login(LoginFormRequest $request){
        $validated=$request->validated();
        $user=User::where('email',$validated['email'])->first();
        if(!$user||!Hash::check($validated['password'],$user->password)){
            return ApiResponse::sendResponse(null,'Invalid credentials',401);
        }
        $token=$user->createToken('auth-token')->plainTextToken;
        return ApiResponse::sendResponse([
            'user'=>$user,
            'token'=>$token,
        ],'User logged in successfully',200);
    }
    public function logout(){
        $user=Auth::user();
        if($user){
            $user->tokens()->delete();
            return ApiResponse::sendResponse(null,'User logged out successfully',200);
        }
    }
}
