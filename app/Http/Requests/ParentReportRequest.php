<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|numeric|exists:students,parent_phone',
            'code' => 'required|numeric|exists:students,code',
        ];
    }
}
