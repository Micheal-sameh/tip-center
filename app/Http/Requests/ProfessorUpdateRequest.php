<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProfessorUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'string|unique:professors,phone',
            'optional_phone' => 'string|unique:professors,phone',
            'subject' => 'string',
            'school' => 'string',
            'birth_date' => 'date',
            'stages' => 'array',
            'stages.*' => '|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
        ];
    }
}
