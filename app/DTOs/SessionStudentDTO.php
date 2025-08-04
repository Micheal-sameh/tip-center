<?php

namespace App\DTOs;

use App\Attributes\HasEmptyPlaceholders;

#[HasEmptyPlaceholders]
class SessionStudentDTO extends DTO
{
    public ?int $session_id;
    public ?int $student_id;
    public ?int $total_paid;
    public ?int $center_price;
    public ?int $professor_price;
    public ?float $printables;
    public ?float $materials;
    public ?int $to_pay;

    public function __construct(
        int $session_id = parent::INT,
        int $student_id = parent::INT,
        int $total_paid = parent::INT,
        int $center_price = parent::INT,
        int $professor_price = parent::INT,
        float $printables = parent::FLOAT,
        float $materials = parent::FLOAT,
        int $to_pay = parent::INT,
    ) {
        parent::__construct(compact(...$this->getParameterList()));
    }
}
