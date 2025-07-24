<?php

namespace App\DTOs;

use App\Attributes\HasEmptyPlaceholders;

#[HasEmptyPlaceholders]
class StudentDTO extends DTO
{
    public ?string $name;
    public ?int $stage;
    public ?string $phone;
    public ?string $parent_phone;
    public ?string $parent_phone_2;
    public ?string $birth_date;
    public ?string $note;

    public function __construct(
        string $name = parent::STRING,
        int $stage = parent::INT,
        string $phone = parent::STRING,
        string $parent_phone = parent::STRING,
        string $parent_phone_2 = parent::STRING,
        string $note = parent::STRING,
        string $birth_date = parent::STRING,
    ) {
        parent::__construct(compact(...$this->getParameterList()));
    }
}
