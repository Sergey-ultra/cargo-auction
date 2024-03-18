<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

class CompanyShowDTO
{
    public function __construct(
        public int $id,
        public string $fullName,
        public ?int $cityId,
        public string $type,
    )
    {
    }
}
