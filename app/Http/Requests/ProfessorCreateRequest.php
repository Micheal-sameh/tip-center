<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProfessorCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|string|unique:professors,phone',
            'optional_phone' => 'nullable|string|unique:professors,phone',
            'subject' => 'required|string',
            'school' => 'required|string',
            'birth_date' => 'nullable|date',
            'stage_schedules' => 'array',
            'stage_schedules.*.stage' => 'required|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'stage_schedules.*.day' => 'required|integer|in:0,1,2,3,4,5,6,7',
            'stage_schedules.*.from' => 'required|date_format:H:i|before:stage_schedules.*.to',
            'stage_schedules.*.to' => 'required|date_format:H:i|after:stage_schedules.*.from',
        ];
    }
}
