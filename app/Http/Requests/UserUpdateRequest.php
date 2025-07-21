<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('id'); // Make sure your route uses {id} in the URL

        return [
            'email' => ['email', 'unique:users,email,'.$id],
            'phone' => ['string', 'regex:/^[0-9]{11}$/', 'unique:users,phone,'.$id],
            'birth_date' => ['date'],
            'role_id' => ['integer', 'exists:roles,id'],
        ];
    }
}
