<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
       $userParam = $this->route('user') ?? $this->route('id');
    
        // If it's a Model instance, get the ID. Otherwise, assume it's already the ID.
        $userId = is_object($userParam) && method_exists($userParam, 'getKey') 
            ? $userParam->getKey() 
            : $userParam;

        return [
            'name' => 'sometimes|required|string|max:255',
            // Make sure the ID exclusion works correctly
            'email' => "sometimes|required|email|unique:users,email,{$userId}",
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
