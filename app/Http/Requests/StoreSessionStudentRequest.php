<?php

namespace App\Http\Requests;

use App\Rules\ActiveSessionRule;
use App\Rules\CanAttendPrice;
use Illuminate\Foundation\Http\FormRequest;

class StoreSessionStudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'session_id' => ['required', 'integer', 'exists:sessions,id', new ActiveSessionRule],
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'total_paid' => ['integer', 'gte:0', new CanAttendPrice($this->session_id)],
            'professor_price' => 'numeric|gte:0',
            'center_price' => 'numeric|gte:0',
            'printables' => 'numeric|gte:0',
            'materials' => 'numeric|gte:0',
            'to_pay' => 'numeric|gte:0',
            'to_pay_center' => 'numeric|gte:0',
            'to_pay_print' => 'numeric|gte:0',
        ];
    }
}
