<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class loginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|exists:users,email',
            'password' => 'required',
            'remember' => 'in:on',
        ];
    }
}
