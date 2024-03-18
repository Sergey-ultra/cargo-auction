<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class LoadingDTO
{
    public function __construct(
        public LocationDTO $location,
        public string $downloadingDateStatus,
        public ?string $downloadingDate,
        public string $downloadingType,
    )
    {
    }
}
