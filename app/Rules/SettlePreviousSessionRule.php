<?php

namespace App\Rules;

use App\Repositories\StudentRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SettlePreviousSessionRule implements ValidationRule
{
    public function __construct(protected int $student_id) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $student = app(StudentRepository::class)->findById($this->student_id);

        $due = $student->toPay()->sum('to_pay');
        if ($value < $due) {
            $fail("Invalid amount please enter $due EGP");
        }
    }
}
