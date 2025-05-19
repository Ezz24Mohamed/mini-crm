<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeFormRequest;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class EmployeeController extends Controller
{
    public function addEmployee(EmployeeFormRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee',


        ]);
        return ApiResponse::sendResponse($user, 'Employee created successfully', 201);
    }
    public function getEmployees()
    {
        $employees = User::where('role', 'employee')->latest()->paginate(10);
        if (!$employees) {
            return ApiResponse::sendResponse(null, 'No employees found', 404);
        }
        return ApiResponse::sendResponse(EmployeeResource::collection($employees), 'Employees fetched successfully', 200);
    }
    public function updateEmployee(Request $request, $id)
    {
        $employee = User::find($id);
        if (!$employee) {
            return ApiResponse::sendResponse(null, 'Employee not found', 404);
        }
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $employee->update([
            'name' => $request->name ?? $employee->name,
            'email' => $request->email ?? $employee->email,
            'password' => $request->password ? Hash::make($request->password) : $employee->password,
        ]);
        return ApiResponse::sendResponse($employee, 'Employee updated successfully', 200);
    }
    public function deleteEmployee($id)
    {
        $employee = User::find($id);
        if (!$employee) {
            return ApiResponse::sendResponse(null, 'Employee not found', 404);
        }
        $employee->delete();
        return ApiResponse::sendResponse(null, 'Employee deleted successfully', 200);
    }

}
