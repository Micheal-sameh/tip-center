<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargeIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date_from' => 'date',
            'date_to' => 'date'.($this->from ? '|after_or_equal:from' : ''),
        ];
    }
}
