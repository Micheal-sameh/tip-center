<?php

namespace App\Http\Requests;

use App\Enums\ProfessorType;
use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProfessorUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'string|unique:professors,phone,'.$this->route('id'),
            'optional_phone' => 'string|unique:professors,phone',
            'subject' => 'string',
            'school' => 'string',
            'birth_date' => 'date',
            'type' => 'required|integer|in:'.implode(',', array_column(ProfessorType::all(), 'value')),
            'stage_schedules' => 'array|min:1',
            'stage_schedules.*.id' => 'nullable|integer|exists:professor_stages,id',
            'stage_schedules.*.stage' => 'integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'stage_schedules.*.day' => 'integer|in:0,1,2,3,4,5,6,7',
            'stage_schedules.*.from' => 'date_format:H:i|before:stage_schedules.*.to',
            'stage_schedules.*.to' => 'date_format:H:i|after:stage_schedules.*.from',
        ];
    }
}
