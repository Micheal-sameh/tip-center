<?php

namespace App\Rules;

use App\Enums\UserStatus;
use App\Repositories\ProfessorRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckActiveProfessorRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $professorRepository = app(ProfessorRepository::class);
        $professor = $professorRepository->findById($value);

        if ((int) $professor->status !== UserStatus::ACTIVE) {
            $fail(__('The selected professor is not active.'));
        }
    }
}
