<?php

declare(strict_types=1);

namespace App\Modules\City\Infrastructure\DTO;

final readonly class CityCoordinatesDTO
{
    public function __construct(public float $longitude, public float $latitude)
    {
    }
}
