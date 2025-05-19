<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\CustomerRequestForm;
use App\Http\Resources\CustomerResource;
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
    public function getCustomers(){
        $user=Auth::user();
        if($user->role==='employee'){
            $customers=Customer::where('assigned_to',$user->id)->latest()->paginate(10);
        }
        else if($user->role==='admin'){
            $customers=Customer::latest()->paginate(10);
        }
        else{
            return ApiResponse::sendResponse(null, 'You are not allowed to view customers', 403);
        }
        if($customers->isEmpty()){
            return ApiResponse::sendResponse(null, 'No customers found', 404);
        }
        return ApiResponse::sendResponse(CustomerResource::collection($customers), 'Customers fetched successfully', 200);
    }
    public function updateCustomer(Request $request,$id){
        $customer=Customer::find($id);
        if(!$customer){
            return ApiResponse::sendResponse(null, 'Customer not found', 404);
        }
        $user=Auth::user();
        if($user->role==='employee' && $customer->assigned_to != $user->id){
            return ApiResponse::sendResponse(null, 'You are not allowed to update this customer', 403);
        }
        $request->validate([
            'name'=>'nullable|string|max:255',
            'email'=>'nullable|email|max:255',
            'phone'=>'nullable|string|max:255',
            'assigned_to'=>'nullable|exists:users,id'
        ]);
        $customer->update([
            'name'=>$request->name ?? $customer->name,
            'email'=>$request->email ?? $customer->email,
            'phone'=>$request->phone ?? $customer->phone,
            'assigned_to'=>$request->assigned_to ?? $customer->assigned_to
        ]);
        return ApiResponse::sendResponse($customer, 'Customer updated successfully', 200);
    }
    public function deleteCustomer($id){
        $customer=Customer::find($id);
        if(!$customer){
            return ApiResponse::sendResponse(null, 'Customer not found', 404);
        }
        $user=Auth::user();
        if($user->role==='employee' && $customer->assigned_to != $user->id){
            return ApiResponse::sendResponse(null, 'You are not allowed to delete this customer', 403);
        }
        $customer->delete();
        return ApiResponse::sendResponse(null, 'Customer deleted successfully', 200);
    }
}
