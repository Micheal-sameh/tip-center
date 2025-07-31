<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class ReportIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'stage' => 'nullable|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'professor' => 'nullable|string',
            'from' => 'nullable|date'.(isset($this->from) && isset($this->to) ? '|before_or_equal:to' : ''),
            'to' => 'nullable|date',
        ];
    }
}
