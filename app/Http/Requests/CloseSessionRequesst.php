<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseSessionRequesst extends FormRequest
{
    public function rules(): array
    {
        return [
            // 'session_id' => 'required|integer|exists:sessions,id',
            'markers' => 'nullable|numeric|min:0',
            'copies' => 'nullable|numeric|min:0',
            'cafeterea' => 'nullable|numeric|min:0',
            'other' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
