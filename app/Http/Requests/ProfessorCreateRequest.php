<?php

namespace App\Http\Requests;

use App\Enums\SessionType;
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
            'birth_date' => 'required|date',
            // 'type' => 'required|integer|in:'.implode(',', array_column(SessionType::all(), 'value')),
            'stage_schedules' => 'array|min:1',
            'stage_schedules.*.stage' => 'integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'stage_schedules.*.day' => 'integer|in:0,1,2,3,4,5,6,7',
            'stage_schedules.*.from' => 'date_format:H:i|before:stage_schedules.*.to',
            'stage_schedules.*.to' => 'date_format:H:i|after:stage_schedules.*.from',
        ];
    }
}
