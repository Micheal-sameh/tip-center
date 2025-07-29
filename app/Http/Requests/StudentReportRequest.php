<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => 'required|string',
            'student_id' => 'nullable|integer|exists:students,id',
            'professor_id' => 'nullable|integer|exists:professors,id',

        ];
    }
}
