<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentSettlementsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'student_id' => 'nullable|exists:students,id',
            'professor_id' => 'nullable|exists:professors,id',
            'session_id' => 'nullable|exists:sessions,id',
            'name' => 'nullable|string|max:255',
        ];
    }
}
