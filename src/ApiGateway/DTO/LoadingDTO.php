<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class LoadingDTO
{
    public function __construct(
        public LocationDTO $location,
        public string $type,
        public ?string $date,
        public ?string $time,
    )
    {
    }
}
