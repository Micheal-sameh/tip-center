<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfessorCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|string|unique:professors,phone',
            'optional_phone' => 'string|unique:professors,phone',
            'subject' => 'required|string',
            'school' => 'required|string',
            'birth_date' => 'required|date',
        ];
    }
}
