<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class StudentCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'stage' => 'required|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'phone' => 'string',
            'parent_phone' => 'string',
            'parent_phone_2' => 'string',
            'birth_date' => 'date',
            'note' => 'string',
        ];
    }
}
