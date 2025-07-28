<?php

namespace App\Rules;

use App\Repositories\SessionRepository;
use App\Repositories\StudentRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanAttendToSession implements ValidationRule
{
    public function __construct(protected int $student_id) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sessionRepository = app(SessionRepository::class);
        $session = $sessionRepository->findById($value);
        $studentRepository = app(StudentRepository::class);
        $student = $studentRepository->findById($this->student_id);

        if ($session->stage != $student->stage) {
            $fail('Student can not attend to this session');
        }
    }
}
