<?php

namespace App\DTOs;

use App\Attributes\HasEmptyPlaceholders;

#[HasEmptyPlaceholders]
class ProfessorDTO extends DTO
{
    public ?string $name;
    public ?string $optional_phone;
    public ?string $phone;
    public ?string $school;
    public ?string $subject;
    public ?string $birth_date;
    // public ?int $type;
    public ?array $stages;
    public ?array $stage_schedules;


    public function __construct(
        string $name = parent::STRING,
        string $optional_phone = parent::STRING,
        string $phone = parent::STRING,
        string $school = parent::STRING,
        string $subject = parent::STRING,
        string $birth_date = parent::STRING,
        // int $type = parent::INT,
        array $stages = parent::ARRAY,
        array $stage_schedules = parent::ARRAY,
    ) {
        parent::__construct(compact(...$this->getParameterList()));
    }
}
