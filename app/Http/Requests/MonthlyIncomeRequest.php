<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyIncomeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'month' => 'date_format:Y-m',
        ];
    }
}
