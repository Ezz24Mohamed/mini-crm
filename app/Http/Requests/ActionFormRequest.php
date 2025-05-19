<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id'=>'required|exists:customers,id',
            'employee_id'=>'required|exists:users,id',
            'action_type'=>'required|in:call,visit,follow_up',
            'result'=>'nullable|string',
        ];
    }
}
