<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class RouteDTO
{
    public function __construct(
        public string $country,
        public int $distance,
        public int $totalDistance,
        public int $fuelConsumption,
        public int $distanceHours,
        public int $distanceMinutes,
    )
    {
    }
}
