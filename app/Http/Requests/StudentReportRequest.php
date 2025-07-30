<?php

namespace App\Http\Requests;

use App\Enums\ReportType;
use Illuminate\Foundation\Http\FormRequest;

class StudentReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'student_id' => 'nullable|integer|exists:students,id',
            'professor_id' => 'nullable|integer|exists:professors,id',
            'type' => 'nullable|integer|in:'.implode(',', array_column(ReportType::all(), 'value')),
        ];
    }
}
