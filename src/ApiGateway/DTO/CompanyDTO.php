<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class CompanyDTO
{
    public function __construct(
        public int $id,
        public string $fullName,
        public string $type,
        public array $contacts,
    )
    {
    }
}
