<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'to_pay' => 'numeric|gte:0',
            'to_pay_center' => 'numeric|gte:0',
            'to_pay_print' => 'numeric|gte:0',
            'to_pay_materials' => 'numeric|gte:0',
        ];
    }
}
