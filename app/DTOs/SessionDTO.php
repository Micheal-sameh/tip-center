<?php

namespace App\DTOs;

use App\Attributes\HasEmptyPlaceholders;

#[HasEmptyPlaceholders]
class SessionDTO extends DTO
{
    public ?int $professor_id;
    public ?int $stage;
    public ?int $professor_price;
    public ?int $center_price;
    public ?float $printables;
    public ?float $materials;
    public ?string $start_at;
    public ?string $end_at;
    public ?int $room;
    public ?int $type;

    public function __construct(
        int $professor_id = parent::INT,
        int $stage = parent::INT,
        int $professor_price = parent::INT,
        int $center_price = parent::INT,
        float $printables = parent::FLOAT,
        float $materials = parent::FLOAT,
        string $start_at = parent::STRING,
        string $end_at = parent::STRING,
        int $room = parent::INT,
        int $type = parent::INT,
    ) {
        parent::__construct(compact(...$this->getParameterList()));
    }
}
