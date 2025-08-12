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

        $requiredAmount = $session->center_price + $session->printables + $session->materials + $session->professor_price;
        if ($value < $requiredAmount) {
            $fail(
                'underpaid: The paid amount is less than the required total of '.number_format($requiredAmount, 2).' EGP.'."\n".
                'We need '.number_format($requiredAmount - $value, 2).' EGP more to attend the session.'
            );

        }
        // if ($value > $requiredAmount) {
        //     $fail('The paid amount is more than the required total of '.number_format($requiredAmount, 2).' EGP'."\n".
        //     'you will need to return '.$value - $requiredAmount."\n"."please enter $requiredAmount EGP and press enter");
        // }
    }
}
