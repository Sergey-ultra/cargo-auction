<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class LocationDTO
{
    public function __construct(
        public int $cityId,
        public string $city,
        public string $region,
        public string $street,
        public float $longitude,
        public float $latitude,
    )
    {
    }
}
