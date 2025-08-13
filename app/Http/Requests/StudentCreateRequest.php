<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class StudentCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:students,name',
            'stage' => 'required|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'phone' => 'required_without:parent_phone|string|unique:students,phone,',
            'parent_phone' => 'required_without:phone|string',
            'parent_phone_2' => 'string',
            'birth_date' => 'date',
            'note' => 'string',
        ];
    }
}
