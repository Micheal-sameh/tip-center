<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class StudentIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => 'string',
            'stage' => 'integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'from' => 'date|'.($this->to ? 'after_or_equal:to' : ''),
            'to' => 'date',
            'sort_by' => 'in:name_asc,name_desc,date_asc,date_desc',
        ];
    }
}
