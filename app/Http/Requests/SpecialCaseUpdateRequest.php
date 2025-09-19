<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialCaseUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'field' => 'required|string|in:center_price,professor_price',
            'value' => '',
            'case_id' => 'required|exists:student_special_cases,id',
        ];
    }
}
