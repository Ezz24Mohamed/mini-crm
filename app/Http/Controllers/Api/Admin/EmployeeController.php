<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeFormRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class EmployeeController extends Controller
{
    public function addEmployee(EmployeeFormRequest $request){
        $validated=$request->validated();
        $user=User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=>Hash::make($validated['password']),
            'role'=>'employee',
            

        ]);
        return ApiResponse::sendResponse($user,'Employee created successfully',201);
    }
    
}
