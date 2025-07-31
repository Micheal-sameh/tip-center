<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class SessionCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'professor_id' => 'required|integer|exists:professors,id',
            'stage' => 'required|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'professor_price' => 'required|numeric|min:0',
            'center_price' => 'required|numeric|min:0',
            'printables' => 'numeric|min:0',
            'materials' => 'numeric|min:0',
            'start_at' => 'date_format:H:i',
            'end_at' => 'date_format:H:i|after:start_at',
        ];
    }
}
