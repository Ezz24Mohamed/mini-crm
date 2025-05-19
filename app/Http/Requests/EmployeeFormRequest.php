<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeFormRequest extends FormRequest
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
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255|confirmed',
        ];
    }
    public function messages(){
        return [
            'name.required' => 'name is required',
            'name.string' => 'name must be a string',
            'name.max' => 'name must be less than 255 characters',
            'email.required' => 'please enter your email',
            'email.email' => 'please enter a valid email',
            'email.max' => 'email must be less than 255 characters',
            'password.required' => 'please enter your password',
            'password.string' => 'password must be a string',
            'password.min' => 'password must be at least 8 characters',
            'password.max' => 'password must be less than 255 characters',
            'password.confirmed' => 'password confirmation does not match',
        ];
    }
}
