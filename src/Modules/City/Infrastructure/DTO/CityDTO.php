<?php

declare(strict_types=1);

namespace App\Modules\City\Infrastructure\DTO;

final readonly class CityDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $region,
        public float $longitude,
        public float $latitude,
    )
    {
    }
}
