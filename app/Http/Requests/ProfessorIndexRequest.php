<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProfessorIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string',
            'stages' => 'array',
            'stages.*' => 'integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
        ];
    }
}
