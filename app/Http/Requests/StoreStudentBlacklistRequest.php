<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentBlacklistRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id|unique:student_blacklists,student_id',
            'reason' => 'required|string|max:1000',
        ];
    }
}
