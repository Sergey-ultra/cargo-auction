<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class LoadInnerDTO
{
    public function __construct(
        public string $cargoType,
        public float $weight,
        public float $volume,
        public string $type,
    )
    {
    }
}
