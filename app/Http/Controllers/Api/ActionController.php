<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionFormRequest;
use Illuminate\Http\Request;
use App\Models\Action;
use App\Helpers\ApiResponse;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ActionResource;

class ActionController extends Controller
{
    public function addAction(ActionFormRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $customer = Customer::find($validated['customer_id']);
        if ($customer->assigned_to != $user->id) {
            return ApiResponse::sendResponse(null, 'You are not assigned to this customer', 403);
        }
        $action = Action::create([
            'customer_id' => $validated['customer_id'],
            'employee_id' => $validated['employee_id'],
            'action_type' => $validated['action_type'],
            'result' => $validated['result'] ?? null,

        ]);
        return ApiResponse::sendResponse($action, 'Action created successfully', 201);
    }
    public function getActions()
    {
        $user = Auth::user();
        $actions = Action::with(['customer'])->where('employee_id', $user->id)->latest()->paginate(10);
        if (!$actions) {
            return ApiResponse::sendResponse(null, 'No actions found', 404);
        }
        return ApiResponse::sendResponse(
            ActionResource::collection($actions),
            'Actions fetched successfully',
            200
        );
    }
    public function updateAction(Request $request, $id)
    {
        $action = Action::find($id);
        if (!$action) {
            return ApiResponse::sendResponse(null, 'Action not found', 404);
        }

        $user = Auth::user();
        $customer = Customer::find($action->customer_id);

        if ($customer->assigned_to != $user->id) {
            return ApiResponse::sendResponse(null, 'You are not assigned to this customer', 403);
        }
        $request->validate([
            'action_type' => 'nullable|in:call,visit,follow up',
            'result' => 'nullable|string',
        ]);
        $action->update([
            'action_type' => $request->action_type?? $action->action_type,
            'result' => $request->result,
        ]);
        
        return ApiResponse::sendResponse(new ActionResource($action), 'Action updated successfully', 200);
    }
}
