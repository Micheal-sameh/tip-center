<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class incomeFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date_from' => 'date',
            'date_to' => 'date'.$this->date_from ? '|after_or_equal:date_from' : '',
        ];
    }
}
