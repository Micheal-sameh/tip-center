<?php

namespace App\Http\Requests;

use App\Enums\SessionType;
use Illuminate\Foundation\Http\FormRequest;

class SessionUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'professor_price' => 'numeric|min:0',
            'center_price' => 'numeric|min:0',
            'printables' => 'numeric|min:0',
            'materials' => 'numeric|min:0',
            'room' => 'numeric|min:0',
            'start_at' => 'nullable|date_format:H:i',
            'end_at' => 'nullable|date_format:H:i|after:start_at',
            'type' => 'in:'.implode(',', array_column(SessionType::all(), 'value')),
        ];
    }
}
