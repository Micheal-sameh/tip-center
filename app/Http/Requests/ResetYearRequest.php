<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetYearRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }
}
