<?php

namespace App\Http\Requests;

use App\Enums\ChargeType;
use Illuminate\Foundation\Http\FormRequest;

class ChargeIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date'.($this->from ? '|after_or_equal:from' : ''),
            'name' => 'nullable|string',
            'type' => 'nullable|in:'.implode(',', array_column(ChargeType::all(), 'value')),
        ];
    }
}
