<?php

namespace App\Http\Requests;

use App\Enums\SessionType;
use App\Enums\StagesEnum;
use App\Rules\CheckActiveProfessorRule;
use Illuminate\Foundation\Http\FormRequest;

class SessionCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'professor_id' => ['required', 'integer', 'exists:professors,id', new CheckActiveProfessorRule],
            'stage' => 'required|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'professor_price' => 'required|numeric|min:0',
            'center_price' => 'required|numeric|min:0',
            'printables' => 'numeric|min:0',
            'materials' => 'numeric|min:0',
            'room' => 'numeric|min:1'.($this->type == SessionType::OFFLINE ? '|required' : ''),
            'start_at' => 'date_format:H:i'.($this->type == SessionType::OFFLINE ? '|required' : ''),
            'end_at' => 'date_format:H:i|after:start_at'.($this->type == SessionType::OFFLINE ? '|required' : ''),
            'type' => 'required|in:'.implode(',', array_column(SessionType::all(), 'value')),
        ];
    }
}
