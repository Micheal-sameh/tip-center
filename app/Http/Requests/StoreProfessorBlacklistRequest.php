<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfessorBlacklistRequest extends FormRequest
{
    public function authorize()
    {
        return true;  // authorize all for now, adjust as needed
    }

    public function rules()
    {
        return [
            'professor_id' => 'required|exists:professors,id',
            'student_id' => 'required|exists:students,id',
            'reason' => 'required|string|max:255',
        ];
    }
}
