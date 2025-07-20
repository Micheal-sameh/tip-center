<?php

namespace App\DTOs;

use App\Attributes\HasEmptyPlaceholders;

#[HasEmptyPlaceholders]
class UserDTO extends DTO
{
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?int $role_id;
    public ?string $birth_date;

    public function __construct(
        string $name = parent::STRING,
        string $email = parent::STRING,
        string $phone = parent::STRING,
        int $role_id = parent::INT,
        string $birth_date = parent::STRING,
    ) {
        parent::__construct(compact(...$this->getParameterList()));
    }
}
