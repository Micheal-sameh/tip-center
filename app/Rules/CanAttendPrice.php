<?php

namespace App\Rules;

use App\Repositories\SessionRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanAttendPrice implements ValidationRule
{
    public function __construct(protected int $session_id) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sessionRepository = app(SessionRepository::class);
        $session = $sessionRepository->findById($this->session_id);

        $requiredAmount = $session->center_price + ($session->printables ?? 0);

        if ($value < $requiredAmount) {
            $fail('The paid amount is less than the required total of $'.number_format($requiredAmount, 2));
        }
        if ($value > $requiredAmount + $session->professor_price) {
            $fail('The paid amount is more than the required total of $'.number_format($requiredAmount + $session->professor_price, 2));
        }
    }
}
