<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class CompanySaveDTO
{
    public function __construct(
        public string $name,
        public int $ownershipId,
        public int $typeId,
        public ?int $cityId,
        public ?string $description,
    ){}
}
