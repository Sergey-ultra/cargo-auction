<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class UnloadingDTO
{
    public function __construct(
        public LocationDTO $location,
        public ?string $date,
        public ?string $time,
    )
    {
    }
}
