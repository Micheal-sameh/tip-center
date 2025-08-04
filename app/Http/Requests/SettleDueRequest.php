<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettleDueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'paid' => 'required|numeric|gt:0',
        ];
    }
}
