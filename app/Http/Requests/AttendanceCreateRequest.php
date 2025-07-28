<?php

namespace App\Http\Requests;

use App\Rules\CanAttendToSession;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'session_id' => ['integer', 'exists:sessions,id', new CanAttendToSession($this->student_id)],
            'student_id' => 'integer|exists:students,id',
        ];
    }
}
