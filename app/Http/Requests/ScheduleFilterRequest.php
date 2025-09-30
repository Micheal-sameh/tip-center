<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'professor_name' => 'nullable|string|max:255',
            'stage' => 'nullable|integer|in:'.implode(',', \App\Enums\StagesEnum::getValues()),
        ];
    }
}
