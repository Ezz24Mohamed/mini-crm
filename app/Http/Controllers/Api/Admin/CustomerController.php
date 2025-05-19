<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\CustomerRequestForm;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function addCustomer(CustomerRequestForm $request)
    {
        $validate = $request->validated();
        $user = Auth::user();
        if (!isset($validate['assigned_to']) && $user->role === 'employee') {
            $validate['assigned_to'] = $user->id;
        }
        if  ( $user->role === 'employee' &&
        isset($validate['assigned_to']) &&
        $validate['assigned_to'] != $user->id ){
            return ApiResponse::sendResponse(null, 'You are not allowed to assign customer to another employee', 403);
        }
        $customer = Customer::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'phone' => $validate['phone'],
            'assigned_to' => $validate['assigned_to'] ?? null,

        ]);
        return ApiResponse::sendResponse($customer, 'customer created successfully', 201);

    }
}
