<?php

namespace App\Http\Requests;

use App\Enums\SessionStatus;
use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class SessionIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'professor_id' => 'nullable|integer|exists:professors,id',
            'status' => 'nullable|in:'.implode(',', array_column(SessionStatus::all(), 'value')),
            'stage' => 'nullable|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
        ];
    }
}
