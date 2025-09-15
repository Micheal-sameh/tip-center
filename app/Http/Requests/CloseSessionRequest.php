<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseSessionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'markers' => 'nullable|numeric',
            'copies' => 'nullable|numeric',
            'cafeterea' => 'nullable|numeric',
            'other' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ];
    }
}
