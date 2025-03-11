<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

final readonly class UnloadingDTO
{
    public function __construct(
        public LocationDTO $location,
        public UnloadingDatesDTO $dates,
    )
    {
    }
}
