<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'stage' => 'required|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'phone' => 'string',
            'parent_phone' => 'string',
            'parent_phone_2' => 'string',
            'birth_date' => 'date',
            'note' => 'string',
        ];
    }
}
