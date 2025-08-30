<?php

namespace App\Rules;

use App\Models\SessionStudent;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanAttendRule implements ValidationRule
{
    public function __construct(protected int $session_id, protected int $student_id) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attend = SessionStudent::where('session_id', $this->session_id)
            ->where('student_id', $this->student_id)->first();

        if ($attend) {
            dd($attend);
            $fail('already attend');
        }
    }
}
