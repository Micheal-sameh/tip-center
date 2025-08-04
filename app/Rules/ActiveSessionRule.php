<?php

namespace App\Rules;

use App\Enums\SessionStatus;
use App\Repositories\SessionRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveSessionRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sessionRepository = app(SessionRepository::class);
        $session = $sessionRepository->findById($value);
        if ($session->status == SessionStatus::FINISHED) {
            $fail('session not active');
        }
    }
}
