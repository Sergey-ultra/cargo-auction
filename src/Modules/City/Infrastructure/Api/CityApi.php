<?php

declare(strict_types=1);

namespace App\Modules\City\Infrastructure\Api;

use App\Modules\City\Infrastructure\Repository\CityRepository;

final readonly class CityApi
{
    public function __construct(private CityRepository $cityRepo)
    {
    }

    public function searchByName(string $name): array
    {
        return $this->cityRepo->searchByName($name);
    }
}
