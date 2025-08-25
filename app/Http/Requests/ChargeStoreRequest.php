<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'amount' => 'required|numeric'.($this->is_gap ? '' : '|gte:0'),
            'is_gap' => 'in:1',
        ];
    }
}
