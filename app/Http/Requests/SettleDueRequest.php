<?php

namespace App\Http\Requests;

use App\Rules\SettlePreviousSessionRule;
use Illuminate\Foundation\Http\FormRequest;

class SettleDueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'paid' => ['required', 'numeric', 'gt:0', new SettlePreviousSessionRule($this->student_id)],
        ];
    }
}
