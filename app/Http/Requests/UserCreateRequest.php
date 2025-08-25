<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'regex:/^[0-9]{11}$/', 'unique:users,phone'],
            'role_id' => 'required|integer|exists:roles,id',
            'birth_date' => 'date',
        ];
    }
}
